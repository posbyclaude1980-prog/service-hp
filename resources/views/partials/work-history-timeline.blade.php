@if($workLogs->isEmpty())
    <p class="text-muted mb-0">Belum ada riwayat pekerjaan teknisi.</p>
@else
    <div class="work-history-timeline">
        @foreach($workLogs as $log)
        <div class="work-history-item mb-3 pb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-{{ $log->action->color() }}">
                        <i class="bi {{ $log->action->icon() }}"></i> {{ $log->action->label() }}
                    </span>
                    <span class="fw-semibold small">{{ $log->title }}</span>
                </div>
                <small class="text-muted">
                    {{ $log->logged_at->format('d/m/Y H:i') }}
                </small>
            </div>
            <div class="small text-muted mt-1">
                <i class="bi bi-person"></i> {{ $log->user->name ?? '-' }}
                <span>({{ $log->user->roleLabel() ?? '-' }})</span>
            </div>
            @if($log->notes)
                <div class="mt-2 small bg-light rounded p-2">{{ $log->notes }}</div>
            @endif
            @if($log->action->value === 'photo' && !empty($log->metadata['path']))
                <div class="mt-2">
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($log->metadata['path']) }}" target="_blank">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($log->metadata['path']) }}" alt="Foto" class="img-thumbnail" style="max-width:140px">
                    </a>
                </div>
            @endif
            @if($log->action->value === 'video' && !empty($log->metadata['path']))
                <div class="mt-2">
                    <video controls class="w-100 rounded" style="max-width:320px">
                        <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($log->metadata['path']) }}">
                    </video>
                </div>
            @endif
            @if($log->action->value === 'spare_part' && !empty($log->metadata['after']))
                <div class="mt-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach($log->metadata['after'] as $item)
                            <li>{{ $item['name'] ?? '-' }} × {{ $item['quantity'] ?? 0 }} — Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($log->action->value === 'status_change' && !empty($log->metadata['to_status']))
                <div class="mt-1 small text-muted">
                    @php $to = \App\Enums\ServiceOrderStatus::tryFrom($log->metadata['to_status']); @endphp
                    Status → <span class="badge bg-{{ $to?->color() ?? 'secondary' }}">{{ $to?->label() ?? $log->metadata['to_status'] }}</span>
                </div>
            @endif
        </div>
        @endforeach
    </div>
@endif
