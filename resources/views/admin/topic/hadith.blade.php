@extends('layouts.admin')

@push('styles')
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <x-admin.page-header :title="'Add-On Hadiths : ' . $topic->slug" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('admin.dashboard')],
        ['label' => convertAsTitle($type), 'link' => route('admin.topics.index', $type)],
        ['label' => $topic->slug],
    ]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card {{ $hadith ? '' : 'd-none' }}">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $url = $hadith?->id
                            ? route('admin.topic-hadith.update', $hadith->id)
                            : route('admin.topic-hadith.store');

                        $method = $hadith?->id ? 'PUT' : 'POST';
                    @endphp

                    <form class="row g-3 needs-validation" novalidate action="{{ $url }}" method="POST">
                        @csrf
                        @method($method)

                        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                        <input type="hidden" name="type" value="{{ $type }}">

                        @if (!$hadith?->id)
                            <div class="col-md-3">
                                <label for="hadith_book_id" class="form-label">Select Book</label>
                                <select id="hadith_book_id" name="hadith_book_id" class="form-control"
                                    style="width: 100%;"></select>
                                <div class="invalid-feedback">
                                    @error('hadith_book_id')
                                        {{ $message }}
                                    @else
                                        {{ 'This field is required.' }}
                                    @enderror
                                </div>

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="hadith_chapter_id" class="form-label">Select Chapter</label>
                                <select id="hadith_chapter_id" name="hadith_chapter_id" class="form-control"
                                    disabled></select>
                                <div class="invalid-feedback">
                                    @error('hadith_chapter_id')
                                        {{ $message }}
                                    @else
                                        {{ 'This field is required.' }}
                                    @enderror
                                </div>

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="hadith_verse_id" class="form-label">Select Hadith</label>
                                <select id="hadith_verse_id" name="hadith_verse_id" class="form-control" disabled></select>
                                <div class="invalid-feedback">
                                    @error('hadith_verse_id')
                                        {{ $message }}
                                    @else
                                        {{ 'This field is required.' }}
                                    @enderror
                                </div>

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <label for="simplified" class="form-label">Hadith Simplified</label>
                            <input type="text" class="form-control  @error('simplified') is-invalid @enderror"
                                name="simplified" value="{{ old('simplified', $hadith?->simplified) }}"
                                placeholder="Simplified" required>

                            <div class="invalid-feedback">
                                @error('simplified')
                                    {{ $message }}
                                @else
                                    {{ 'This field is required.' }}
                                @enderror
                            </div>

                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="translation_json" class="form-label">Translations (JSON)</label>
                            <textarea id="translation_json" class="form-control  @error('title') is-invalid @enderror" name="translation_json"
                                rows="6">{{ old('translation_json', $hadith?->translation_json) }}</textarea>

                            <div class="invalid-feedback">
                                @error('translation_json')
                                    {{ $message }}
                                @else
                                    {{ 'This field is required.' }}
                                @enderror
                            </div>

                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.alert type="error" />

                    <a href="{{ route('admin.topic-hadith.index', [$topic->id, 0]) }}" class="btn btn-primary btn-sm ms-1"
                        id="createBtn">
                        Create
                    </a>
                    <button type="button" class="btn btn-info btn-sm" id="reorderBtn"
                        onclick="sortData('{{ route('admin.topic-hadith.sort') }}', '{{ csrf_token() }}')">
                        Sort
                    </button>

                    <x-admin.table :headers="['#', 'Simplified', 'Translation (JSON)', 'Surah', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Select2 height fix for Bootstrap 5 */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            /* Bootstrap rounded-md */
        }

        .select2-selection__rendered {
            line-height: 30px !important;
        }

        .select2-selection__arrow {
            height: 44px !important;
            top: 6px;
        }
    </style>

    <script>
        // Bootstrap 5 form validation
        (function() {
            'use strict';

            // Fetch all forms with class 'needs-validation'
            var forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission if invalid
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    // Add Bootstrap validation classes
                    form.classList.add('was-validated');
                }, false);
            });

        })();
    </script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.topic-hadith.dataTable') }}",
                    data: {
                        topic_id: "{{ $topic->id }}"
                    }
                },
                columns: [{
                        data: 'position',
                        name: 'position',
                    },
                    {
                        data: 'simplified',
                        name: 'simplified'
                    },
                    {
                        data: 'translation_json',
                        name: 'translation_json'
                    },
                    {
                        data: 'hadith',
                        name: 'hadith',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data && data.hadith_book_id && data.hadith_chapter_id) {
                                return `${data.chapter.book.slug}: ${data.chapter.chapter_number}: ${data.hadith_number}`;
                            }
                            return '-';
                        },
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            const url =
                                "{{ route('admin.topic-hadith.index', [$topic->id, ':id']) }}"
                                .replace(':id', row.id);
                            const deleteUrl = "{{ route('admin.topic-hadith.destroy', [':id']) }}"
                                .replace(':id', row.id);

                            return `<a href="${url}" class="btn btn-link">Edit</a>|
                                <button class="btn btn-link text-danger" onclick="deleteItem('${deleteUrl}', '{{ csrf_token() }}')">Delete</button>`;
                        }
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
                rowId: 'id',
                drawCallback: function(settings) {
                    makeSortable();
                },
            });
        });

        $('#hadith_book_id').select2({
            placeholder: 'Search books',
            ajax: {
                url: "{{ route('fetch.hadith.books') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(item => ({
                            id: item.hadith_book_id,
                            text: `${item.hadith_book_id} - ${item.name}`
                        }))
                    };
                },
                cache: true
            }
        });

        $('#hadith_chapter_id').select2({
            placeholder: 'Search chapter',
            ajax: {
                url: "{{ route('fetch.hadith.chapters') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        name: params.term,
                        hadith_book_id: $('#hadith_book_id').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: (data || []).map(item => {
                            const translation = item.translations?.[0] || {};
                            return {
                                id: item.id,
                                text: `${item.chapter_number} - ${translation.name ?? item.name}`
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#hadith_verse_id').select2({
            placeholder: 'Search hadith',
            ajax: {
                url: "{{ route('fetch.hadith.verses') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        hadith_chapter_id: $('#hadith_chapter_id').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: (data || []).map(item => {
                            return {
                                id: item.id,
                                text: `${item.hadith_number} - ${item.text}`
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#hadith_book_id').on('change', function() {
            $('#hadith_chapter_id').prop('disabled', !$(this).val()).val(null).trigger('change');
        });

        $('#hadith_chapter_id').on('change', function() {
            $('#hadith_verse_id').prop('disabled', !$(this).val()).val(null).trigger('change');
        });
    </script>
@endpush
