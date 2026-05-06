@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('customer.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifikasi</h5>
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Tandai semua dibaca</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notif)
                    <div class="border-bottom p-3 {{ $notif->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas {{ $notif->data['icon'] ?? 'fa-bell' }} fa-2x text-{{ $notif->data['color'] ?? 'primary' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $notif->data['title'] }}</div>
                                <div class="text-muted">{{ $notif->data['body'] }}</div>
                                <div class="small text-muted mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                            @if(!$notif->read_at)
                            <div>
                                <form action="{{ route('notifications.mark-read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link">Tandai dibaca</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada notifikasi</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection