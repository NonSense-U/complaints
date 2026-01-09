<?php

namespace App\Services\Complaint;

use App\Jobs\UploadComplaintMediaSet;
use App\Models\Media;
use App\Repositories\ComplaintRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Complaint;
use App\Notifications\RequiredAdditionalInfoNotification;
use App\Notifications\SendNewNoteNotification;
use App\Notifications\SendOtpNotification;

class ComplaintService
{
    public function __construct(
        protected ComplaintRepository $repo
    ) {}

    public function create(array $data, array $images, array $documents)
    {
        try{
            return DB::transaction(function () use ($data, $images, $documents) {
            // Generate reference number first
            $referenceNumber = $this->generateReference();
            
            // Ensure the reference number is included in the data
            $complaintData = array_merge($data, [
                'reference_number' => $referenceNumber,
                'status' => $data['status'] ?? 'pending'
            ]);
            
            // Create the complaint with all data including reference_number
            $complaint = $this->repo->create($complaintData);


        if (!empty($images)) {
            $mediaFiles = [];

            foreach ($images as $file) {
                $path = $file->store('temp'); // stores in storage/app/temp

                $mediaFiles[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }

            UploadComplaintMediaSet::dispatch($complaint, $mediaFiles, 'Complaint Images');
        }


            // حفظ المستندات
            foreach ($documents as $doc) {
                $path = $doc->store("complaints/{$complaint->id}", 'public');
                Media::create([
                    'complaint_id' => $complaint->id,
                    'file_path' => $path,  //تعديل جديد
                    'original_name' => $doc->getClientOriginalName(),
                    'mime_type' => $doc->getMimeType(),
                    'size' => $doc->getSize(),
                    'media_type' => 'document'
                ]);
            }

            return $complaint->load('media');
        });
    }   catch (\Exception $e) {

            // يرجع الخطأ الحقيقي بدل ما ينبلع داخل transaction
            throw $e;
        }
    }

    private function generateReference()
    {
        return 'CMP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public function getAll()
{
    return Complaint::with(['media','gov'])->oldest()->get();
}

public function find($id)
{
    return Complaint::with(['media','notes'])->find($id);
}

public function updateStatus($id, $status)
{
    $complaint = Complaint::find($id);

    if (!$complaint) return null;

    $complaint->update(['status' => $status]);

    return $complaint->load('media');
}

//get user complaints
public function getUserComplaints($userId)
{
    return Complaint::where('user_id', $userId)
        ->with('media')
        ->orderBy('created_at', 'asc') //من الاقدم
        ->get();
}
// get complaints for specific gov
public function 
getByGov($govId)
{
    return Complaint::with(['media','user', 'notes'])->where('gov_id', $govId)->orderBy('created_at','desc')->get();
}

// update status but record who changed and optional note
public function updateStatusWithNote($id, $status, $note = null, $changedByUser = null)
{
    $complaint = Complaint::find($id);
    if (!$complaint) return null;

    $complaint->update(['status' => $status]);

    // record note (you need notes table or use comments table) — simple event log in a 'notes' table recommended
    if ($note) {
        $complaint->notes()->create([
            'user_id' => $changedByUser?->id,
            'note' => $note,
        ]);
    }

    return $complaint->load('media','notes','user');
}

public function addNote($id, $note, $user)
{
    $complaint = Complaint::find($id);
    if (!$complaint) return null;
    $complaint->notes()->create([
        'user_id' => $user->id,
        'note' => $note,
    ]);

    $complaint->user->notify(new SendNewNoteNotification($complaint, $note));

    return $complaint->load('notes');
}

public function requestAdditionalInfo($id, $message, $user)
{
    $complaint = Complaint::find($id);
    if (!$complaint) return null;

    // create a request_info record or reuse notes with type 'request_info'
    $complaint->notes()->create([
        'user_id' => $user->id,
        'note' => $message,
        'type' => 'request_info' // if your notes table supports this
    ]);

    $complaint->user->notify(new RequiredAdditionalInfoNotification($complaint, $message));


    return true;
}


// ---------for admin
public function getAllWithFilters(array $filters = [])
{
    $query = Complaint::with('media','user','gov');

    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }
    if (!empty($filters['gov_id'])) {
        $query->where('gov_id', $filters['gov_id']);
    }
    if (!empty($filters['date_from'])) {
        $query->whereDate('created_at', '>=', $filters['date_from']);
    }
    if (!empty($filters['date_to'])) {
        $query->whereDate('created_at', '<=', $filters['date_to']);
    }
    if (!empty($filters['q'])) {
        $query->where(function($q) use ($filters) {
            $q->where('body','like','%'.$filters['q'].'%')
              ->orWhere('reference_number','like','%'.$filters['q'].'%');
        });
    }

    return $query->orderBy('created_at','desc')->paginate(25);
}

public function generateStats($period = [])
{
    $from = $period['from'] ?? now()->subMonth()->toDateString();
    $to = $period['to'] ?? now()->toDateString();

    $byStatus = Complaint::selectRaw('status, count(*) as count')
                        ->whereBetween('created_at', [$from, $to])
                        ->groupBy('status')->get();

    $byGov = Complaint::selectRaw('gov_id, count(*) as count')
                      ->whereBetween('created_at', [$from, $to])
                      ->groupBy('gov_id')->with('gov')->get();

    $daily = Complaint::selectRaw('DATE(created_at) as date, count(*) as count')
                     ->whereBetween('created_at', [$from, $to])
                     ->groupBy('date')
                     ->orderBy('date')->get();

    return [
        'by_status' => $byStatus,
        'by_gov' => $byGov,
        'daily' => $daily,
    ];
}

}
