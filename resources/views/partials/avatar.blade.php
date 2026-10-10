@php $size = $size ?? 36; @endphp

@if ($user->foto_url)
    <img src="{{ $user->foto_url }}" alt="{{ $user->name }}"
         class="rounded-circle object-fit-cover flex-shrink-0"
         style="width: {{ $size }}px; height: {{ $size }}px;">
@else
    <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center flex-shrink-0 fw-semibold"
          style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ round($size * 0.42) }}px;">
        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
    </span>
@endif