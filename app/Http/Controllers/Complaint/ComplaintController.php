<?php

namespace App\Http\Controllers\Complaint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Services\Complaint\ComplaintService;
use App\Http\Requests\Complaint\UpdateStatusRequest;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ComplaintStatusUpdated;

class ComplaintController extends Controller
{
    public function __construct(
        protected ComplaintService $service
    ) {}

    public function store(ComplaintRequest $request)
    {
        $data = [
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'gov_id' => $request->gov_id,
            'location' => $request->location,
            'body' => $request->body,
        ];

        $complaint = $this->service->create(
            $data,
            $request->file('images',[]),
            $request->file('documents',[])
            
        );

        return response()->json([
            'message' => 'تم تقديم الشكوى بنجاح',
            'reference' => $complaint->reference_number,
            'complaint' => new ComplaintResource($complaint),
        ], 201);
    }


    public function index()
{
    $complaints = $this->service->getAll();

    return ComplaintResource::collection($complaints);
}


public function show($id)
{
    $complaint = $this->service->find($id);

    if (!$complaint) {
        return response()->json(['message' => 'الشكوى غير موجودة'], 404);
    }

    return new ComplaintResource($complaint);
}

//get user complaints
public function userComplaints()
{
    $userId = Auth::user()->id;
    $complaints = $this->service->getUserComplaints($userId);

    return response()->json([
        'message' => 'تم جلب شكاوي المستخدم',
        'complaints' => ComplaintResource::collection($complaints)
    ]);
}
public function updateStatus(UpdateStatusRequest $request, $id)
{
    $complaint = $this->service->updateStatus($id, $request->status);
    

    if (!$complaint) {
        return response()->json(['message' => 'الشكوى غير موجودة'], 404);
    }
    //send not...
    $complaint->user->notify(
        new ComplaintStatusUpdated($complaint)
    );

    return response()->json([
        'message' => 'تم تحديث حالة الشكوى بنجاح',
        'complaint' => new ComplaintResource($complaint)
    ]);
}
}