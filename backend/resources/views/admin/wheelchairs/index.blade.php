@extends('admin.layouts.app')

@section('title', 'Wheelchairs')
@section('page-title', 'Wheelchairs')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Wheelchairs</h3>
        <a href="{{ route('admin.wheelchairs.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Wheelchair
        </a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Station</th>
                    <th>Battery</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wheelchairs as $wheelchair)
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 600;">{{ $wheelchair->code }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $wheelchair->brand }} {{ $wheelchair->model }}</div>
                        </div>
                    </td>
                    <td>{{ $wheelchair->wheelchairType->name ?? 'N/A' }}</td>
                    <td>{{ $wheelchair->station->name ?? 'N/A' }}</td>
                    <td>
                        @php
                            $battery = $wheelchair->battery_capacity;
                            $color = $battery >= 70 ? 'success' : ($battery >= 30 ? 'warning' : 'danger');
                        @endphp
                        <span class="badge badge-{{ $color }}">{{ $battery }}%</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $wheelchair->status === 'available' ? 'success' : ($wheelchair->status === 'rented' ? 'info' : ($wheelchair->status === 'maintenance' ? 'warning' : 'danger')) }}">
                            {{ ucfirst($wheelchair->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="{{ route('admin.wheelchairs.edit', $wheelchair) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            @if($wheelchair->bookings_count == 0)
                            <form action="{{ route('admin.wheelchairs.destroy', $wheelchair) }}" method="POST" 
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
                    <td colspan="6" class="text-center text-muted">No wheelchairs found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($wheelchairs->hasPages())
    <div class="pagination">
        <div class="pagination-info">
            Showing {{ $wheelchairs->firstItem() }} to {{ $wheelchairs->lastItem() }} of {{ $wheelchairs->total() }} results
        </div>
        <div class="pagination-buttons">
            {{ $wheelchairs->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
