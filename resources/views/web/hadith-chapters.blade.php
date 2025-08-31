@extends('layouts.web')

@section('title', __('app.hadith') . ' | ' . ($book->translation?->name ?? $book->name))

@section('content')
    <x-web.container>
        <x-web.index-card class="b-top">
            <x-web.chapter-header :name="(optional($book->translation)->name ? $book->translation->name . ' | ' : '') . $book->name" :writer="$book->translation?->writer ?? $book->writer">
                <x-web.input-search />
            </x-web.chapter-header>

            <x-web.index-list class="col-two">
                @foreach ($book->chapters as $item)
                    <x-web.index-list-item :href="route('hadith.chapter.verses', ['book' => $book->slug, 'chapter' => $item->id])">
                        {{ $loop->iteration }}. {{ $item->translation?->name ?? $item->name }}
                    </x-web.index-list-item>
                @endforeach
            </x-web.index-list>
        </x-web.index-card>
    </x-web.container>
@endsection

@push('scripts')
    <script>
        function searchHadithByNumber() {
            const number = parseInt($('#hadith-number').val(), 10);

            if (isNaN(number) || number < 1) {
                alert("Please enter a valid Hadith number.");
                return;
            }

            window.location.href = "{{ route('hadith.book.verse', [$book->id, ':verse']) }}"
                .replace(':verse', number);
        }
    </script>
@endpush
