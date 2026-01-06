@extends('admin.layouts.app')

@section('title', 'Stations')
@section('page-title', 'Stations')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Stations</h3>
        <a href="{{ route('admin.stations.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Station
        </a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>City</th>
                    <th>Wheelchairs</th>
                    <th>Operating Hours</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stations as $station)
                <tr>
                    <td>
                        <div class="flex items-center gap-sm">
                            <div class="avatar avatar-sm" style="background: var(--primary);">
                                <i class="fa-solid fa-location-dot" style="font-size: 0.75rem;"></i>
                            </div>
                            <div>
                                <div>{{ $station->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $station->name_ar }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $station->city }}</td>
                    <td>{{ $station->wheelchairs_count }}</td>
                    <td>{{ $station->operating_hours }}</td>
                    <td>
                        <span class="badge badge-{{ $station->is_active ? 'success' : 'danger' }}">
                            {{ $station->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            @if($station->wheelchairs_count == 0 && $station->bookings_count == 0)
                            <form action="{{ route('admin.stations.destroy', $station) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this station?')">
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
                    <td colspan="6" class="text-center text-muted">No stations found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stations->hasPages())
    <div class="pagination">
        <div class="pagination-info">
            Showing {{ $stations->firstItem() }} to {{ $stations->lastItem() }} of {{ $stations->total() }} results
        </div>
        <div class="pagination-buttons">
            {{ $stations->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
