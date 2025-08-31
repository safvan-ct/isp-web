@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Staffs" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Staffs']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <button type="button" onclick="createUpdate(0)" class="btn btn-primary btn-sm" id="createBtn">Create</button>

                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.alert type="error" />

                    <x-admin.table :headers="['#', 'First Name', 'Last Name', 'Email', 'Phone', 'Role', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal>
        <input type="hidden" id="edit_id">

        <x-admin.input name="first_name" label="First Name" error="0" placeholder="First Name" required />
        <x-admin.input name="last_name" label="Last Name" error="0" placeholder="Last Name" required />
        <x-admin.input type="email" name="email" label="Email" error="0" placeholder="Email" required />
        <x-admin.input name="phone" label="Phone" error="0" placeholder="Phone" required />
        <x-admin.input name="password" label="Password" error="0" placeholder="Password" required />

        <div class="form-floating mb-2">
            <select class="form-select" name="role" id="role">
                <option value="">Select Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <label for="role">Role</label>
        </div>

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
                ajax: "{{ route('admin.staffs.datatable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        searchable: false,
                        render: (data, type, row) => {
                            return row.roles?.map(role => role.name).join(', ');
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url = "{{ route('admin.staffs.active', ':id') }}".replace(':id', row
                                .id);

                            return `<button onclick="toggleActive('${url}')" class="${text} btn btn-link">${label}</button>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const url = "{{ route('admin.staffs.destroy', ':id') }}".replace(':id',
                                row
                                .id);

                            return `<button type="button" class="btn btn-link" onclick="createUpdate(${row.id})"
                                data-first_name="${row.first_name}" data-last_name="${row.last_name}" data-email="${row.email}"
                                data-phone="${row.phone}" data-role="${row.role}" id="editBtn${row.id}">Edit</button>

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
            $('.modal-title').text(isCreate ? 'Create User' : 'Update User');
            $('.create').toggleClass('d-none', !isCreate);

            $('#edit_id').val(id);
            $('#first_name').val($(`#editBtn${id}`).data('first_name') ?? '');
            $('#last_name').val($(`#editBtn${id}`).data('last_name') ?? '');
            $('#email').val($(`#editBtn${id}`).data('email') ?? '');
            $('#phone').val($(`#editBtn${id}`).data('phone') ?? '');
            $('#password').val('');

            $('#role').val($(`#editBtn${id}`).data('role') ?? '');
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                password: $('#password').val(),
                role: $('#role').val(),
            };

            if (!data.first_name.trim() && !data.last_name.trim()) {
                toastr.error('Please fill Name field');
                return;
            }

            if (!data.email.trim()) {
                toastr.error('Please fill Email field');
                return;
            }

            if (!data.phone.trim()) {
                toastr.error('Please fill Phone field');
                return;
            }

            if (!data.role.trim()) {
                toastr.error('Please select Role field');
                return;
            }

            const url = data.id != 0 ?
                `{{ route('admin.staffs.update', ':id') }}`.replace(':id', data.id) :
                "{{ route('admin.staffs.store') }}";

            method = data.id != 0 ? 'PUT' : 'POST';

            storeData(data, url, method);
        }
    </script>
@endpush
