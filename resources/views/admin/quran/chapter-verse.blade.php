@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Verses" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Verses']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <select id="chapterFilter" class="form-select selectFilter form-select-sm w-auto">
                    <option value="0">Select All Chapter</option>
                    @foreach ($chapters as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>

                <select class="form-select selectFilter form-select-sm w-auto" id="languageFilter">
                    @foreach (config('app.languages') as $key => $language)
                        <option value="{{ $key }}">{{ $language }}</option>
                    @endforeach
                </select>

                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.table :headers="['#', 'Title', 'Status', 'Actions']"> </x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal size="modal-lg">
        <input type="hidden" id="edit_id">
        <label for="text">Verse</label>
        <textarea rows="10" id="text" class="form-control mb-2"></textarea>

        <div class="d-flex justify-content-end">
            <x-admin.button class="btn btn-primary" onclick="createUpdatePost()">Save</x-admin.button>
        </div>
    </x-admin.modal>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (localStorage.getItem("QuranVerseChapter")) {
                $('#chapterFilter').val(localStorage.getItem("QuranVerseChapter"));
            }

            if (localStorage.getItem("QuranVerseLanguage")) {
                $('#languageFilter').val(localStorage.getItem("QuranVerseLanguage"));
            }

            const table = $('#dataTable').DataTable({
                pageLength: 25,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.quran-verses.dataTable') }}",
                    data: function(d) {
                        d.chapter_id = $('#chapterFilter').val();
                        d.lang = $('#languageFilter').val();
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
                        data: 'text',
                        name: 'text'
                    },
                    {
                        data: 'is_active',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url = "{{ route('admin.quran-verses.status', ':id') }}".replace(
                                ':id', row.id);
                            if ($('#languageFilter').val() != 'ar') {
                                url = "{{ route('admin.quran-verse-translations.status', ':id') }}"
                                    .replace(':id', row.id);
                            }

                            return `<button onclick="toggleActive('${url}')" class="${text} btn btn-link">${label}</button>`;
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<button onclick="createUpdate(${row.id})" class="btn btn-link" id="editBtn${row.id}" data-text="${row.text}">Edit</button>`;
                        }
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
                rowId: 'id'
            });

            $('#chapterFilter').on('change', function() {
                localStorage.setItem("QuranVerseChapter", $(this).val());
                table.draw();
            });

            $('#languageFilter').on('change', function() {
                localStorage.setItem("QuranVerseLanguage", $(this).val());
                table.draw();
            });
        })

        function createUpdate(id) {
            toastr.clear();

            const isCreate = id === 0;
            if (isCreate) {
                toastr.error('Verse not found');
                return;
            }

            $('.createUpdate').modal('show');
            $('.modal-title').text(isCreate ? 'Create' : 'Update');
            $('.create').toggleClass('d-none', !isCreate);

            $('#edit_id').val(isCreate ? '' : id);
            $('#text').val(isCreate ? '' : $(`#editBtn${id}`).data('text'));
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                text: $('#text').val(),
            };
            if (!data.id) {
                toastr.error('Verse not found');
                return;
            }

            if (!data.text.trim()) {
                toastr.error('Please fill Verse field');
                return;
            }

            let url = "{{ route('admin.quran-verses.update', ':id') }}".replace(':id', data.id);
            if ($('#languageFilter').val() != 'ar') {
                url = "{{ route('admin.quran-verse-translations.update', ':id') }}".replace(':id', data.id);
            }

            storeData(data, url, 'PUT');
        }
    </script>
@endpush
