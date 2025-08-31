@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Settings" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Settings']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <button type="button" onclick="createUpdate(0)" class="btn btn-primary btn-sm" id="createBtn">Create</button>

                <div class="card-body">
                    <x-admin.table :headers="['#', 'Key', 'Value', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal>
        <input type="hidden" id="edit_id">
        <x-admin.input name="key" label="Key" error="0" placeholder="Key" required />
        <x-admin.input name="value" label="Value" error="0" placeholder="Value" required />
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
                ajax: "{{ route('admin.settings.datatable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'key',
                        name: 'key'
                    },
                    {
                        data: 'value',
                        name: 'value'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const url = "{{ route('admin.settings.destroy', ':id') }}".replace(
                                ':id',
                                row.id);

                            return `<button type="button" class="btn btn-link" onclick="createUpdate(${row.id})"
                                data-key="${row.key}" data-value="${row.value}" id="editBtn${row.id}">Edit</button>

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
            $('.modal-title').text(isCreate ? 'Create Settings' : 'Update Settings');

            $('#edit_id').val(id);
            $('#key').val($(`#editBtn${id}`).data('key') ?? '');
            $('#value').val($(`#editBtn${id}`).data('value') ?? '');
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                key: $('#key').val(),
                value: $('#value').val(),
            };

            if (!data.key.trim()) {
                toastr.error('Please fill Key field');
                return;
            }

            const url = data.id != 0 ?
                `{{ route('admin.settings.update', ':id') }}`.replace(':id', data.id) :
                "{{ route('admin.settings.store') }}";

            method = data.id != 0 ? 'PUT' : 'POST';

            storeData(data, url, method);
        }
    </script>
@endpush
