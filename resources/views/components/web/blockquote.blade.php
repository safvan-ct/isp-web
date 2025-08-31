@props(['type', 'id', 'text', 'translation' => '', 'reference' => ''])

<blockquote class="m-0 mt-1 notranslate">
    <span onclick="openAddOnModal('{{ $type }}', {{ $id }})" style="cursor: pointer;">
        <p dir="rtl" class="text-quran fs-5 mb-1">{{ $text }}</p>
        {{ $translation }}
    </span>

    <div class="d-flex align-items-center justify-content-between mt-0">
        <p class="text-muted small fst-italic notranslate">🔖 {{ $reference }}</p>

        <div class="d-flex align-items-center gap-2">
            <a href="javascript:void(0);" title="View"
                onclick="openAddOnModal('{{ $type }}', {{ $id }})">
                <i class="far fa-eye fs-5"></i>
            </a>

            <x-web.actions :type="$type" :item="$id" />
        </div>
    </div>
</blockquote>
