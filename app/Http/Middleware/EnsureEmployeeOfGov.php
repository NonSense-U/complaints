<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Complaint;

class EnsureEmployeeOfGov 
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role?->name !== 'Employee') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // If route has {id} complaint, ensure complaint.gov_id == user.gov_id
        $complaintId = $request->route('id') ?? $request->route('complaint'); // try both
        if ($complaintId) {
            $complaint = Complaint::find($complaintId);
            if (!$complaint) {
                return response()->json(['message' => 'Complaint not found'], 404);
            }
            if ($complaint->gov_id != $user->gov_id) {
                return response()->json(['message' => 'Forbidden - not your government complaints'], 403);
            }
        }

        return $next($request);
    }
}
