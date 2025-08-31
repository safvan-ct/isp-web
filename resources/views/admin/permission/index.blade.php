@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Permissions" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Permissions']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <button type="button" onclick="createUpdate(0)" class="btn btn-primary btn-sm" id="createBtn">Create</button>

                <div class="card-body">
                    <x-admin.table :headers="['#', 'Name', 'Guard', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal>
        <input type="hidden" id="edit_id">
        <x-admin.input name="name" label="Name" error="0" placeholder="Name" required />
        <x-admin.button class="btn btn-primary" onclick="createUpdatePost()">Save</x-admin.button>
    </x-admin.modal>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true, // Load only visible rows
                destroy: true,
                responsive: true,
                ajax: "{{ route('admin.permissions.datatable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'guard_name',
                        name: 'guard_name',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const url = "{{ route('admin.permissions.destroy', ':id') }}".replace(
                                ':id', row.id);

                            return `<button type="button" class="btn btn-link" onclick="createUpdate(${row.id})" data-name="${row.name}" id="editBtn${row.id}">Edit</button>

                                <button type="button" class="btn btn-link text-danger" onclick="deleteItem('${url}', '{{ csrf_token() }}')">
                                    Delete
                                </button>`;
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
            });
        });

        function createUpdate(id) {
            toastr.clear();
            const isCreate = id === 0;

            $('.createUpdate').modal('show');
            $('.modal-title').text(isCreate ? 'Create Permission' : 'Update Permission');

            $('#edit_id').val(id);
            $('#name').val($(`#editBtn${id}`).data('name') ?? '');
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                name: $('#name').val(),
            };

            if (!data.name.trim()) {
                toastr.error('Please fill Name field');
                return;
            }

            const url = data.id != 0 ?
                `{{ route('admin.permissions.update', ':id') }}`.replace(':id', data.id) :
                "{{ route('admin.permissions.store') }}";

            method = data.id != 0 ? 'PUT' : 'POST';

            storeData(data, url, method);
        }
    </script>
@endpush
