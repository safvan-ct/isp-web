@extends('layouts.admin')

@section('content')
    <x-admin.page-header :title="convertAsTitle($type)" :breadcrumb="[['label' => 'Dashboard', 'link' => route('admin.dashboard')], ['label' => convertAsTitle($type)]]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <button type="button" onclick="createUpdate(0)" class="btn btn-primary btn-sm" id="createBtn">Create</button>
                <button type="button" class="btn btn-info btn-sm" id="reorderBtn"
                    onclick="sortData('{{ route('admin.topics.sort') }}', '{{ csrf_token() }}')">
                    Sort
                </button>

                @if ($type !== 'menu')
                    <select class="form-select selectFilter form-select-sm w-auto" id="parentFilter">
                        <option value="">Select Parent</option>
                        @foreach ($parents as $item)
                            <option value="{{ $item->id }}">{{ $item->slug }}</option>
                        @endforeach
                    </select>
                @endif

                <div class="card-body">
                    <x-admin.alert type="success" />
                    <x-admin.alert type="error" />

                    <x-admin.table :headers="['Position', 'Slug', 'Status', 'Add-On', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal>
        <input type="hidden" id="edit_id">

        @if ($type !== 'menu')
            <div class="form-floating mb-2 create">
                <select class="form-select form-control" id="parent_id">
                    <option value="">Select Parent</option>
                    @foreach ($parents as $item)
                        <option value="{{ $item->id }}">{{ $item->slug }}</option>
                    @endforeach
                </select>
                <label for="parent_id">Select Parent</label>
            </div>
        @endif

        <x-admin.input name="slug" label="Slug" error="0" placeholder="Slug" required />

        @if (in_array($type, ['menu', 'module']))
            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" id="is_primary" name="is_primary" value="1">
                <label class="form-check-label" for="is_primary">Mark As Primary</label>
            </div>
        @endif

        <div class="d-flex justify-content-end">
            <x-admin.button class="btn btn-primary" onclick="createUpdatePost()">Save</x-admin.button>
        </div>
    </x-admin.modal>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (localStorage.getItem("TopicParent{{ $type }}")) {
                $('#parentFilter').val(localStorage.getItem("TopicParent{{ $type }}"));
            }

            const table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true, // Load only visible rows
                destroy: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.topics.dataTable') }}",
                    data: function(d) {
                        d.type = "{{ $type }}";
                        d.parent_id = $('#parentFilter').val();
                    }
                },
                columns: [{
                        data: 'position',
                        name: 'position',
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        render: function(data, type, row) {
                            return `<i class="text-muted">#${row.id}</i> - ${row.slug} `;
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
                            let url = "{{ route('admin.topics.status', ':id') }}".replace(':id',
                                row
                                .id);

                            return `<button onclick="toggleActive('${url}')" class="${text} btn btn-link">${label}</button>`;
                        }
                    },
                    {
                        data: null,
                        name: 'add_on',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            const videoUrl = "{{ route('admin.topic-video.index', ':id') }}"
                                .replace(':id', row.id);
                            const hadithUrl = "{{ route('admin.topic-hadith.index', ':id') }}"
                                .replace(':id', row.id);
                            const quranUrl = "{{ route('admin.topic-quran.index', ':id') }}"
                                .replace(':id', row.id);

                            return `<a href="${quranUrl}" class="btn btn-link text-info">Quran</a>|<a href="${hadithUrl}" class="btn btn-link text-info">Hadiths</a>|<a href="${videoUrl}" class="btn btn-link text-info">Videos</a>`;
                        },
                        visible: {{ $type === 'answer' ? 'true' : 'false' }}

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const url =
                                "{{ route('admin.topic-translations.index', [$type, ':id']) }}"
                                .replace(':id', row.id);

                            return `<a href="${url}" class="btn btn-link">Translations</a>|<button type="button" class="btn btn-link" onclick="createUpdate(${row.id})"
                                data-slug="${row.slug}" data-primary="${row.is_primary}" id="editBtn${row.id}">Edit</button>`;
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
                rowId: 'id', // ensures each row has id="rowID"
                drawCallback: function(settings) {
                    makeSortable(); // rebind sortable after every draw
                },
            });

            $('#parentFilter').on('change', function() {
                localStorage.setItem("TopicParent{{ $type }}", $(this).val());
                table.draw();
            });
        });

        function createUpdate(id) {
            let title = "{{ convertAsTitle($type) }}";
            toastr.clear();
            const isCreate = id === 0;

            $('.createUpdate').modal('show');
            $('.modal-title').text(isCreate ? `Create ${title}` : `Update ${title}`);
            $('.create').toggleClass('d-none', !isCreate);

            $('#edit_id').val(id);
            $('#slug').val($(`#editBtn${id}`).data('slug') ?? '');
            $('#is_primary').prop('checked', $(`#editBtn${id}`).data('primary') ?? false);
            $('#parent_id').val(localStorage.getItem("TopicParent{{ $type }}") ?? '');
        }

        function createUpdatePost() {
            const data = {
                _token: "{{ csrf_token() }}",
                id: $('#edit_id').val(),
                slug: $('#slug').val(),
                is_primary: $('#is_primary').is(':checked') ? 1 : 0
            };

            if ('{{ $type }}' !== 'menu' && data.id == 0) {
                data.parent_id = $('#parent_id').val();

                if (!data.parent_id) {
                    toastr.error('Please select parent');
                    return;
                }
            }

            if (!data.slug.trim()) {
                toastr.error('Please Slug field');
                return;
            }

            const url = data.id != 0 ?
                `{{ route('admin.topics.update', [$type, ':id']) }}`.replace(':id', data.id) :
                "{{ route('admin.topics.store', $type) }}";

            method = data.id != 0 ? 'PUT' : 'POST';

            storeData(data, url, method);
        }
    </script>
@endpush
