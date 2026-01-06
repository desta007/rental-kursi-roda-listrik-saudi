@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Customers</h3>
        <div class="flex gap-sm">
            <form class="flex gap-sm" method="GET">
                <select name="status" class="form-input form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                <input type="text" name="search" class="form-input" style="width: 200px;" 
                       placeholder="Search..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Bookings</th>
                    <th>Verification</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center gap-sm">
                            <div class="avatar avatar-sm">{{ substr($user->name, 0, 2) }}</div>
                            <div>
                                <div>{{ $user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $user->identity_type ? ucfirst($user->identity_type) : 'No ID' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.875rem;">{{ $user->email ?? '-' }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $user->phone ?? '-' }}</div>
                    </td>
                    <td>{{ $user->bookings_count }}</td>
                    <td>
                        <span class="badge badge-{{ $user->verification_status === 'verified' ? 'success' : ($user->verification_status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($user->verification_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="pagination">
        <div class="pagination-info">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
        </div>
    </div>
    @endif
</div>
@endsection
