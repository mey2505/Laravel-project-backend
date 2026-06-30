@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Activity Log</h3>
        <div class="card-tools">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="input-group input-group-sm" style="width:250px">
                <input type="text" name="search" class="form-control" placeholder="Search events..." value="{{ $search }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Entity</th>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Changes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><small>{{ $log->created_at->format('M d, Y H:i') }}</small></td>
                    <td>{{ $log->user->name ?? '<span class="text-muted">System</span>' }}</td>
                    <td>
                        <span class="badge badge-{{ match($log->event) {
                            'created' => 'success', 'updated' => 'info',
                            'deleted' => 'danger', 'login' => 'primary',
                            default   => 'secondary'
                        } }}">{{ $log->event }}</span>
                    </td>
                    <td><small>{{ class_basename($log->auditable_type) }}</small></td>
                    <td><small>#{{ $log->auditable_id }}</small></td>
                    <td><small>{{ $log->ip_address }}</small></td>
                    <td>
                        @if($log->new_values)
                        <button type="button" class="btn btn-xs btn-outline-secondary"
                            data-bs-toggle="tooltip"
                            title="{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}">
                            View
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No audit logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $logs->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>$(function(){ $('[data-bs-toggle="tooltip"]').tooltip() });</script>
@endpush
