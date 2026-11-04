{{-- resources/views/components/admin/field.blade.php --}}
@props(['label' => ''])

<div {{ $attributes }}>
    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-200 mb-2">
        {{ $label }}
    </label>
    {{ $slot }}
</div>