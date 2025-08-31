@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Activity Log" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => 'Activity Log']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 flex-wrap" style="flex-grow: 1; max-width: calc(100% - 150px);">
                        <select class="form-select text-capitalize" style="max-width: 15%" id="log_table_id">
                            <option value="0">Select Table</option>
                            @foreach ($logTables as $item)
                                <option value="{{ $item }}" @selected($logName == $item) class="text-capitalize">
                                    {{ str_replace('_', ' ', $item) }}
                                </option>
                            @endforeach
                        </select>

                        <select class="form-select text-capitalize" style="max-width: 15%" id="log_event_id">
                            <option value="0">Select Action</option>
                            @foreach ($logEvents as $item)
                                <option value="{{ $item }}" @selected($eventName == $item) class="text-capitalize">
                                    {{ str_replace('_', ' ', $item) }}
                                </option>
                            @endforeach
                        </select>

                        <select class="form-select text-capitalize" style="max-width: 15%" id="log_user_id">
                            <option value="0">Select User</option>
                            @foreach ($logUsers as $item)
                                <option value="{{ $item->id }}" @selected($causerId == $item->id)>
                                    {{ $item->first_name }} {{ $item->last_name }} - {{ $item->id }}
                                </option>
                            @endforeach
                        </select>

                        <select class="form-select text-capitalize" style="max-width: 15%" id="log_subject_id">
                            <option value="0">Select Subject</option>
                            @foreach ($logSubjects as $item)
                                <option value="{{ $item }}" @selected($subjectId == $item)>{{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <x-admin.link-button text="Get Logs" onclick="redirectToPage()" class="btn btn-primary"
                        style="white-space: nowrap;" />
                </div>

                <div class="card-body">
                    <x-admin.alert type="success" />

                    <x-admin.table :headers="['#', 'Table', 'Row ID', 'User ID', 'Action', 'Change']">
                        @foreach ($activityLogs as $log)
                            @php $class = $log->event == 'delete' ? 'text-danger' : ''; @endphp

                            <tr>
                                <th class="{{ $class }}">{{ $loop->iteration }}</th>
                                <td class="{{ $class }}">{{ $log->log_name }}</td>
                                <td class="{{ $class }}">{{ $log->subject_id }}</td>
                                <td class="{{ $class }}">{{ $log->causer_id }}</td>
                                <td class="{{ $class }}">{{ $log->event }}</td>
                                <td>
                                    @if ($log->event == 'delete')
                                        <span class="text-danger">Deleted</span>
                                    @else
                                        <x-admin.link-button onclick="view({{ $log->properties }})" type="link"
                                            text="View" />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });

        function redirectToPage() {
            const url = "{{ route('admin.activity-log', [':logName', ':eventName', ':causerId', ':subjectId']) }}"
                .replace(':logName', $('#log_table_id').val())
                .replace(':eventName', $('#log_event_id').val())
                .replace(':causerId', $('#log_user_id').val())
                .replace(':subjectId', $('#log_subject_id').val());

            window.location.href = url;
        }

        function view(change) {
            const jsonString = JSON.stringify(change, null, 2);

            $('#viewModal').modal('show');
            $('#viewModal .modal-body').html('<pre>' + jsonString + '</pre>');
        }
    </script>
@endpush
