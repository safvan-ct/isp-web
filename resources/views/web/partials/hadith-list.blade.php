@foreach ($result as $hadith)
    @php
        $book = isset($book) ? $book : $hadith->book;
        $chapter = isset($chapter) ? $chapter : $hadith->chapter;
    @endphp

    <x-web.ayah-card class="pb-0">
        <x-web.hadith-text :hadith="$hadith->text" :translation="$hadith->translation?->text" :number="$hadith->hadith_number" :heading="$hadith->heading" :headingTranslation="$hadith->translation?->heading" />
        <hr>

        <div class="d-flex align-items-center justify-content-between mt-0">
            <x-web.hadith-reference :book="$book->translation?->name ?? $book->name" :volume="$hadith->volume" :chapter="$chapter->chapter_number . ' - ' . $chapter->translation?->name ?? $chapter->name" :hadith="$hadith->hadith_number"
                :status="$hadith->status" />

            @if (!isset($action))
                <x-web.actions :type="'hadith'" :item="$hadith->id" :bookmarked="isset($bookmarked) ? $bookmarked : false" :liked="isset($liked) ? $liked : false" />
            @endif
        </div>
    </x-web.ayah-card>
@endforeach
