@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Chapters" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Chapters']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.table :headers="['#', 'Title', 'Revelation Place', 'N.O of Verses', 'Status', 'Actions']"> </x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal>
        <input type="hidden" id="edit_id">
        <label for="name">Chapter Name</label>
        <input type="text" id="name" class="form-control mb-2" placeholder="Chapter Name">

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
                    url: "{{ route('admin.quran-chapters.dataTable') }}",
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
                        data: 'revelation_place',
                        name: 'revelation_place',
                    },
                    {
                        data: 'no_of_verses',
                        name: 'no_of_verses',
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url = "{{ route('admin.quran-chapters.status', ':id') }}".replace(
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
                                "{{ route('admin.quran-chapter-translations.index', [':id']) }}"
                                .replace(':id', row.id);
                            return `<a href="${url}" class="btn btn-link">Translations</a>|
                                <button onclick="createUpdate(${row.id})" class="btn btn-link" id="editBtn${row.id}" data-name="${row.name}">Edit</button>`;
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
            $('#name').val(isCreate ? '' : $(`#editBtn${id}`).data('name'));
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                name: $('#name').val(),
            };

            if (!data.id) {
                toastr.error('Chapter not found');
                return;
            }

            if (!data.name.trim()) {
                toastr.error('Please fill Name field');
                return;
            }

            const url = "{{ route('admin.quran-chapters.update', ':id') }}".replace(':id', data.id);

            storeData(data, url, "PUT");
        }
    </script>
@endpush
