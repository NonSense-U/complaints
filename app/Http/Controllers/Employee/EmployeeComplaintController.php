<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Complaint\ComplaintService;
use App\Http\Requests\Employee\UpdateStatusRequest;
use App\Http\Requests\Employee\AddNoteRequest;
use App\Http\Requests\Employee\RequestInfoRequest;
use App\Http\Resources\ComplaintResource;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ComplaintStatusUpdated;

class EmployeeComplaintController extends Controller
{
    public function __construct(protected ComplaintService $service)
    {
        // ensure only employees of gov can update specific complaint
        // $this->middleware('empOfGov')->only(['updateStatus','addNote','requestMoreInfo','show']);
    }

    public function index()
    {
        $user = Auth::user();
        $complaints = $this->service->getByGov($user->gov_id);
        return ComplaintResource::collection($complaints);
    }

    public function show($id)
    {
        $c = $this->service->find($id);
        if (!$c) return response()->json(['message'=>'Not found'],404);
        return new ComplaintResource($c);
    }

/*public function updateStatus(UpdateStatusRequest $request, $id)
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
}*/

    public function addNote(AddNoteRequest $request, $id)
    {
        $this->service->addNote($id, $request->note, Auth::user());
        return response()->json(['message'=>'تم إضافة الملاحظة']);
    }

    public function requestMoreInfo(RequestInfoRequest $request, $id)
    {
        $this->service->requestAdditionalInfo($id, $request->message, Auth::user());
        return response()->json(['message'=>'تم طلب معلومات إضافية من المواطن']);
    }
}
