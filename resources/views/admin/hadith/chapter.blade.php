@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Chapters" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Chapters']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <select class="form-select selectFilter form-select-sm w-auto" id="bookFilter">
                    <option value="">Select Book</option>
                    @foreach ($books as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>

                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.table :headers="['#', 'Title', 'Status', 'Actions']"> </x-admin.table>
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
            if (localStorage.getItem("HadithChapter")) {
                $('#bookFilter').val(localStorage.getItem("HadithChapter"));
            }

            const table = $('#dataTable').DataTable({
                pageLength: 25,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.hadith-chapters.dataTable') }}",
                    data: function(d) {
                        d.book_id = $('#bookFilter').val();
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'is_active',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url = "{{ route('admin.hadith-chapters.status', ':id') }}".replace(
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
                                "{{ route('admin.hadith-chapter-translations.index', [':id']) }}"
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
                rowId: 'id',
            });

            $('#bookFilter').on('change', function() {
                localStorage.setItem("HadithChapter", $(this).val());
                table.draw();
            });
        });

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

            const url = "{{ route('admin.hadith-chapters.update', ':id') }}".replace(':id', data.id);
            storeData(data, url, 'PUT');
        }
    </script>
@endpush
