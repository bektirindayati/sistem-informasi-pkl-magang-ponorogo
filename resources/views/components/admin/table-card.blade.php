{{--Komponen: <x-admin.table-card icon="🟢" title="Daftar Pemagang Aktif" description="...">
                  <table>...</table>
              </x-admin.table-card><table> --}}

              @props(['icon' => null, 'title', 'description' => null, 'titleSize' => 'text-base'])

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-5 md:p-6 border-b border-slate-100 flex items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-900 {{ $titleSize }}">
                @if($icon){{ $icon }} @endif{{ $title }}
            </h3>
            @if($description)
                <p class="text-xs text-slate-500 mt-0.5">{{ $description }}</p>
            @endif
        </div>
        {{ $action ?? '' }}
    </div>

    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>