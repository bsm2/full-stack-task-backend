<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Traits\ApiResponse;

class LogController extends Controller
{
    use ApiResponse;
    public function index()
    {
        return $this->success(AuditLog::latest('id')->paginate(12));
    }
}
