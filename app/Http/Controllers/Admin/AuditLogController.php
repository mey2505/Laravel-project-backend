<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    protected AuditLogService $service;

    public function __construct(AuditLogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $logs   = $this->service->getPaginated(20, $search);
        return view('admin.audit-logs.index', compact('logs', 'search'));
    }
}
