<?php

namespace App\Repositories;

use App\Models\Complaint;

class ComplaintRepository
{
    public function create(array $data)
    {
        return Complaint::create($data);
    }

    public function findByReference(string $ref)
    {
        return Complaint::where('reference_number', $ref)->with('media')->first();
    }
}
