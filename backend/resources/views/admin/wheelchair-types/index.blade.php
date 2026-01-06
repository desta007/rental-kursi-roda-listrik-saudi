@extends('admin.layouts.app')

@section('title', 'Wheelchair Types')
@section('page-title', 'Wheelchair Types')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Wheelchair Types</h3>
        <a href="{{ route('admin.wheelchair-types.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Type
        </a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Daily Rate</th>
                    <th>Weekly Rate</th>
                    <th>Deposit</th>
                    <th>Units</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wheelchairTypes as $type)
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 600;">{{ $type->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $type->name_ar }}</div>
                        </div>
                    </td>
                    <td>SAR {{ number_format($type->daily_rate) }}</td>
                    <td>SAR {{ number_format($type->weekly_rate) }}</td>
                    <td>SAR {{ number_format($type->deposit_amount) }}</td>
                    <td>{{ $type->wheelchairs_count }}</td>
                    <td>
                        <span class="badge badge-{{ $type->is_active ? 'success' : 'danger' }}">
                            {{ $type->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="{{ route('admin.wheelchair-types.edit', $type) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            @if($type->wheelchairs_count == 0)
                            <form action="{{ route('admin.wheelchair-types.destroy', $type) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No wheelchair types found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
