@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Books" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Books']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.table :headers="[
                        '#',
                        'Name',
                        'Slug',
                        'Writer',
                        'Writer Death',
                        'No of chapters',
                        'No of hadiths',
                        'Status',
                        'Actions',
                    ]"> </x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal>
        <input type="hidden" id="edit_id">

        <label for="book_name">Book Name</label>
        <input type="text" id="book_name" class="form-control mb-2" placeholder="Book Name">

        <label for="writer">Writer</label>
        <input type="text" id="writer" class="form-control mb-2" placeholder="Writer">

        <div class="d-flex justify-content-end">
            <x-admin.button class="btn btn-primary" onclick="createUpdatePost()">Save</x-admin.button>
        </div>
    </x-admin.modal>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.hadith-books.dataTable') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                    },
                    {
                        data: 'writer',
                        name: 'writer',
                    },
                    {
                        data: 'writer_death_year',
                        name: 'writer_death_year',
                    },
                    {
                        data: 'chapter_count',
                        name: 'chapter_count',
                    },
                    {
                        data: 'hadith_count',
                        name: 'hadith_count',
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url = "{{ route('admin.hadith-books.status', ':id') }}".replace(
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
                                "{{ route('admin.hadith-book-translations.index', [':id']) }}"
                                .replace(':id', row.id);
                            return `<a href="${url}" class="btn btn-link">Translations</a>|
                                <button onclick="createUpdate(${row.id})" class="btn btn-link" id="editBtn${row.id}"
                                data-name="${row.name}" data-writer="${row.writer}">Edit</button>`;
                        }
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
            });
        })

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
            $('#book_name').val(isCreate ? '' : $(`#editBtn${id}`).data('name'));
            $('#writer').val(isCreate ? '' : $(`#editBtn${id}`).data('writer'));
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                name: $('#book_name').val(),
                writer: $('#writer').val(),
            };

            if (!data.id) {
                toastr.error('Chapter not found');
                return;
            }

            if (!data.name.trim() || !data.writer.trim()) {
                toastr.error('Please fill required fields');
                return;
            }

            const url = "{{ route('admin.hadith-books.update', ':id') }}".replace(':id', data.id);

            storeData(data, url, "PUT");
        }
    </script>
@endpush
