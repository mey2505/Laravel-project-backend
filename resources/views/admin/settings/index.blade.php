@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    {{-- General Settings --}}
    <div class="card card-primary card-outline">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-cog mr-1"></i> General Settings</h3></div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Site Name</label>
                <div class="col-sm-9">
                    <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? config('app.name') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Site Description</label>
                <div class="col-sm-9">
                    <textarea name="site_description" class="form-control" rows="2">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Business Settings --}}
    <div class="card card-success card-outline">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-store mr-1"></i> Business Settings</h3></div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Currency Symbol</label>
                <div class="col-sm-3">
                    <input type="text" name="currency" class="form-control" value="{{ $settings['currency'] ?? '$' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tax Rate (%)</label>
                <div class="col-sm-3">
                    <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] ?? 0 }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Default Shipping Fee</label>
                <div class="col-sm-3">
                    <input type="number" step="0.01" name="shipping_fee" class="form-control" value="{{ $settings['shipping_fee'] ?? 0 }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Order Prefix</label>
                <div class="col-sm-3">
                    <input type="text" name="order_prefix" class="form-control" value="{{ $settings['order_prefix'] ?? 'ORD-' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Timezone</label>
                <div class="col-sm-4">
                    <select name="timezone" class="form-control">
                        @foreach(timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ ($settings['timezone'] ?? config('app.timezone')) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Mail Settings --}}
    <div class="card card-info card-outline">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-envelope mr-1"></i> Email Settings</h3></div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">From Name</label>
                <div class="col-sm-6">
                    <input type="text" name="mail_from_name" class="form-control" value="{{ $settings['mail_from_name'] ?? config('mail.from.name') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">From Email</label>
                <div class="col-sm-6">
                    <input type="email" name="mail_from_address" class="form-control" value="{{ $settings['mail_from_address'] ?? config('mail.from.address') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-1"></i> Save Settings</button>
        </div>
    </div>
</form>
@endsection
