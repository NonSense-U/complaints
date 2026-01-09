<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Complaint\ComplaintService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(protected ComplaintService $service) {}

    public function allComplaints(Request $request)
    {
        // optional filters: status, gov_id, date_from, date_to, q
        $filters = $request->only(['status','gov_id','date_from','date_to','q']);
        $complaints = $this->service->getAllWithFilters($filters);
        return response()->json($complaints);
    }

    public function showComplaint($id)
    {
        $c = $this->service->find($id);
        if (!$c) return response()->json(['message'=>'Not found'],404);
        return response()->json($c);
    }

    public function stats(Request $request)
    {
        $stats = $this->service->generateStats($request->only(['from','to']));
        return response()->json($stats);
    }
}
