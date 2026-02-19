@php
    $banner = \App\Models\AdBanner::getRandomBySize($size);
@endphp

@if($banner)
    <div class="ad-banner-container mb-3" data-banner-id="{{ $banner->id }}">
        <a href="{{ route('banners.click', $banner->id) }}" target="_blank">
            <img src="{{ asset('storage/' . $banner->image_path) }}" 
                 alt="{{ $banner->title }}"
                 class="img-fluid"
                 style="max-width: 100%;">
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/banners/{{ $banner->id }}/view', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        });
    </script>
@endif
