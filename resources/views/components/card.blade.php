{{--
    Kartu standar: rounded-2xl, border tipis, shadow halus.
    <x-card> ... </x-card>              -> padding standar p-5 sm:p-6
    <x-card :pad="false"> ... </x-card> -> tanpa padding (atur sendiri di dalam)
    Class tambahan bisa ditambahkan, mis. <x-card class="lg:col-span-2">
--}}
@props(['pad' => true])

<div {{ $attributes->class([
    'bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden',
    'p-5 sm:p-6' => $pad,
]) }}>
    {{ $slot }}
</div>