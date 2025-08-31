@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Roles" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Roles']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <button type="button" onclick="createUpdate(0)" class="btn btn-primary btn-sm" id="createBtn">Create</button>

                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.alert type="error" />

                    <x-admin.table :headers="['#', 'Name', 'Permissions', 'Actions']"> </x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal size="modal-lg">
        <input type="hidden" id="edit_id">

        <x-admin.input name="name" label="Name" error="0" placeholder="Name" required />

        @foreach ($permissions as $key => $items)
            <b class="text-capitalize">{{ $key }}</b>
            <div class="d-flex flex-wrap mb-2">
                @foreach ($items as $item)
                    <div class="form-check d-inline-block me-3">
                        <input class="form-check-input input-primary" type="checkbox" id="permission_{{ $item['id'] }}"
                            name="permissions[]" value="{{ $item['name'] }}" />
                        <label class="form-check-label" for="permission_{{ $item['id'] }}">{{ $item['name'] }}</label>
                    </div>
                @endforeach
            </div>
        @endforeach

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
                ajax: "{{ route('admin.roles.datatable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        orderable: false,
                        searchable: false,
                        render: (data, type, row) => {
                            return row.permissions?.map(item =>
                                `<span class='badge bg-primary me-2'>${item.name}</span>`).join(
                                '');
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (!['Developer', 'Owner', 'Customer'].includes(row.name)) {
                                const url = "{{ route('admin.roles.destroy', ':id') }}".replace(
                                    ':id',
                                    row.id);
                                const permissions = row.permissions?.map(item => item.name).join(
                                    ',');

                                return `<button type="button" class="btn btn-link" onclick="createUpdate(${row.id})"
                                data-name="${row.name}" data-permissions="${permissions}" id="editBtn${row.id}">Edit</button>

                                <button type="button" class="btn btn-link text-danger" onclick="deleteItem('${url}', '{{ csrf_token() }}')">
                                    Delete
                                </button>`;
                            }

                            return '-';
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
            $('.modal-title').text(isCreate ? 'Create Role' : 'Update Role');

            $('#edit_id').val(id);
            $('#name').val($(`#editBtn${id}`).data('name') ?? '');

            const permissions = $(`#editBtn${id}`).data('permissions') ? $(`#editBtn${id}`).data('permissions').split(',') :
                [];

            document.querySelectorAll('input[name="permissions[]"]').forEach(input => {
                input.checked = permissions.includes(input.value);
            });
        }

        function createUpdatePost() {
            const permissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
                .map(input => input.value);

            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                name: $('#name').val(),
                permissions: permissions,
            };

            if (!data.name.trim()) {
                toastr.error('Please fill Name field');
                return;
            }

            const url = data.id != 0 ?
                `{{ route('admin.roles.update', ':id') }}`.replace(':id', data.id) :
                "{{ route('admin.roles.store') }}";

            method = data.id != 0 ? 'PUT' : 'POST';

            storeData(data, url, method);
        }
    </script>
@endpush
