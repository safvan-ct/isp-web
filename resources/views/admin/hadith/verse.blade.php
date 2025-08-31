@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Verses" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Verses']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5">
                            <select class="form-select selectFilter" id="bookFilter">
                                <option value="">Select Book</option>
                                @foreach ($books as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-5">
                            <select class="form-select selectFilter" id="chapterFilter">
                                <option value="">Select Chapter</option>
                            </select>
                        </div>
                    </div>

                    <x-admin.alert type="success" />
                    <x-admin.table :headers="['#', 'Heading', 'Hadith', 'Volume', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal size="modal-lg">
        <input type="hidden" id="edit_id">

        <label for="heading">Hadith Heading</label>
        <input type="text" id="heading" class="form-control mb-2" placeholder="Hadith Heading">

        <label for="text">Hadith</label>
        <textarea class="form-control" rows="12" id="text"></textarea>

        <div class="d-flex justify-content-end mt-2">
            <x-admin.button class="btn btn-primary" onclick="createUpdatePost()">Save</x-admin.button>
        </div>
    </x-admin.modal>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookElement = document.getElementById('bookFilter');
            bookChoices = new Choices(bookElement, {
                removeItemButton: true,
                placeholderValue: 'Select Book',
                searchPlaceholderValue: 'Search...',
                shouldSort: false,
            });
            if (localStorage.getItem("HadithVerseBook")) {
                bookChoices.setChoiceByValue(localStorage.getItem("HadithVerseBook"));
            }

            const chapterElement = document.getElementById('chapterFilter');
            chapterChoices = new Choices(chapterElement, {
                removeItemButton: true,
                placeholderValue: 'Select Chapter',
                searchPlaceholderValue: 'Search...',
                shouldSort: false,
            });

            fetchChapters();
        });

        $(document).ready(function() {
            const table = $('#dataTable').DataTable({
                pageLength: 25,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.hadith-verses.dataTable') }}",
                    data: function(d) {
                        d.book_id = $('#bookFilter').val();
                        d.chapter_id = localStorage.getItem("HadithVerseChapter") ? localStorage
                            .getItem("HadithVerseChapter") : $('#chapterFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'heading',
                        name: 'heading'
                    },
                    {
                        data: 'text',
                        name: 'text'
                    },
                    {
                        data: 'volume',
                        name: 'volume'
                    },
                    {
                        data: 'is_active',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url = "{{ route('admin.hadith-verses.status', ':id') }}".replace(
                                ':id', row.id);

                            return `<button onclick="toggleActive('${url}')" class="${text} btn btn-link">${label}</button>`;
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            const url =
                                "{{ route('admin.hadith-verse-translations.index', [':id']) }}"
                                .replace(':id', row.id);
                            return `<a href="${url}" class="btn btn-link">Translations</a>|
                                <button onclick="createUpdate(${row.id})" class="btn btn-link" id="editBtn${row.id}" data-heading="${row.heading}" data-text="${row.text}">Edit</button>`;
                        }
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
            });

            $('#bookFilter').on('change', function() {
                localStorage.setItem("HadithVerseBook", $(this).val());
                localStorage.setItem("HadithVerseChapter", '');
                table.draw();
                fetchChapters();
            });

            $('#chapterFilter').on('change', function() {
                localStorage.setItem("HadithVerseChapter", $(this).val());
                table.draw();
            });
        });

        function fetchChapters() {
            chapterChoices.removeActiveItems();
            chapterChoices.clearChoices();

            if (!$('#bookFilter').val()) {
                return;
            }

            const url = "{{ route('admin.hadith-verses.chapter', ':bookId') }}".replace(':bookId', $('#bookFilter').val());
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const savedChapter = localStorage.getItem("HadithVerseChapter");

                    const options = [{
                            value: '',
                            label: 'Select Chapter',
                            selected: !savedChapter, // Select this if no saved value
                            disabled: false, // Set to true if you want it unselectable
                        },
                        ...data.map(item => ({
                            value: item.id,
                            label: item.name,
                            selected: item.id == savedChapter
                        }))
                    ];

                    chapterChoices.setChoices(options, 'value', 'label', true);
                })
                .catch(error => {
                    toastr.error(error);
                });

            if (localStorage.getItem("HadithVerseChapter")) {
                chapterChoices.setChoiceByValue(localStorage.getItem("HadithVerseChapter"));
            }
        }

        function createUpdate(id) {
            toastr.clear();

            const isCreate = id === 0;
            if (isCreate) {
                toastr.error('Chapter not found');
                return;
            }

            $('.createUpdate').modal('show');
            $('.modal-title').text(isCreate ? 'Create' : 'Update');
            $('.create').toggleClass('d-none', !isCreate);

            $('#edit_id').val(isCreate ? '' : id);
            $('#heading').val(isCreate ? '' : $(`#editBtn${id}`).data('heading'));
            $('#text').val(isCreate ? '' : $(`#editBtn${id}`).data('text'));
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                heading: $('#heading').val(),
                text: $('#text').val(),
            };

            if (!data.id) {
                toastr.error('Chapter not found');
                return;
            }

            if (!data.text.trim()) {
                toastr.error('Please fill hadith field');
                return;
            }

            const url = "{{ route('admin.hadith-verses.update', ':id') }}".replace(':id', data.id);

            storeData(data, url, 'PUT');
        }
    </script>
@endpush
