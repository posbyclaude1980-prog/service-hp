<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1>{{ $title }}</h1>
        @isset($breadcrumbs)
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @foreach($breadcrumbs as $label => $url)
                    @if($loop->last)
                        <li class="breadcrumb-item active">{{ is_int($label) ? $url : $label }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
        @endisset
    </div>
    @isset($actions)
    <div class="d-flex gap-2 flex-wrap">
        {!! $actions !!}
    </div>
    @endisset
</div>
