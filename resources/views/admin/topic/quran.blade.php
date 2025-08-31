@extends('layouts.admin')

@push('styles')
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <x-admin.page-header :title="'Add-On Quran Verse : ' . $topic->slug" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('admin.dashboard')],
        ['label' => convertAsTitle($type), 'link' => route('admin.topics.index', $type)],
        ['label' => $topic->slug],
    ]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card {{ $quran ? '' : 'd-none' }}">
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
                        $url = $quran?->id
                            ? route('admin.topic-quran.update', $quran->id)
                            : route('admin.topic-quran.store');

                        $method = $quran?->id ? 'PUT' : 'POST';
                    @endphp

                    <form class="row g-3 needs-validation" novalidate action="{{ $url }}" method="POST">
                        @csrf
                        @method($method)

                        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                        <input type="hidden" name="type" value="{{ $type }}">

                        @if (!$quran?->id)
                            <div class="col-md-4">
                                <label for="chapter_id" class="form-label">Select Chapter</label>
                                <select id="chapter_id" name="chapter_id" class="form-control"
                                    style="width: 100%;"></select>
                                <div class="invalid-feedback">
                                    @error('chapter_id')
                                        {{ $message }}
                                    @else
                                        {{ 'This field is required.' }}
                                    @enderror
                                </div>

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label for="quran_verse_id" class="form-label">Select Ayah</label>
                                <select id="quran_verse_id" name="quran_verse_id" class="form-control" disabled></select>
                                <div class="invalid-feedback">
                                    @error('quran_verse_id')
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
                            <label for="simplified" class="form-label">Ayah Simplified</label>
                            <input type="text" class="form-control  @error('simplified') is-invalid @enderror"
                                name="simplified" value="{{ old('simplified', $quran?->simplified) }}"
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
                                rows="6">{{ old('translation_json', $quran?->translation_json) }}</textarea>

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

                    <a href="{{ route('admin.topic-quran.index', [$topic->id, 0]) }}" class="btn btn-primary btn-sm ms-1"
                        id="createBtn">
                        Create
                    </a>
                    <button type="button" class="btn btn-info btn-sm" id="reorderBtn"
                        onclick="sortData('{{ route('admin.topic-quran.sort') }}', '{{ csrf_token() }}')">
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
                    url: "{{ route('admin.topic-quran.dataTable') }}",
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
                        data: 'quran',
                        name: 'quran',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data && data.quran_chapter_id && data.number_in_chapter) {
                                return `${data.quran_chapter_id}:${data.number_in_chapter}`;
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
                                "{{ route('admin.topic-quran.index', [$topic->id, ':id']) }}"
                                .replace(':id', row.id);
                            const deleteUrl = "{{ route('admin.topic-quran.destroy', [':id']) }}"
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

        $('#chapter_id').select2({
            placeholder: 'Search chapter by number',
            ajax: {
                url: "{{ route('fetch.quran.chapters') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        chapter_id: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(item => ({
                            id: item.quran_chapter_id,
                            text: `${item.quran_chapter_id} - ${item.name}`
                        }))
                    };
                },
                cache: true
            }
        });

        $('#quran_verse_id').select2({
            placeholder: 'Search ayah by number',
            ajax: {
                url: "{{ route('fetch.quran.ayahs') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        ayah_number: params.term,
                        chapter_id: $('#chapter_id').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: `(${item.number_in_chapter}) ${item.text}`
                        }))
                    };
                },
                cache: true
            }
        });

        $('#chapter_id').on('change', function() {
            $('#quran_verse_id').prop('disabled', !$(this).val()).val(null).trigger('change');
        });
    </script>
@endpush
