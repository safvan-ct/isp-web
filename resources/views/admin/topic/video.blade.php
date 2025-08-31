@extends('layouts.admin')

@push('styles')
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <x-admin.page-header :title="'Add-On videos : ' . $topic->slug" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('admin.dashboard')],
        ['label' => convertAsTitle($type), 'link' => route('admin.topics.index', $type)],
        ['label' => $topic->slug],
    ]" />

    <div class="row">
        <div class="col-sm-12">
            <x-admin.alert type="success" />
            <x-admin.alert type="error" />

            <div class="card {{ $video ? '' : 'd-none' }}">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $url = $video?->id
                            ? route('admin.topic-video.update', $video->id)
                            : route('admin.topic-video.store');

                        $method = $video?->id ? 'PUT' : 'POST';
                    @endphp

                    <form class="row g-3 needs-validation" novalidate action="{{ $url }}" method="POST">
                        @csrf
                        @method($method)

                        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                        <input type="hidden" name="type" value="{{ $type }}">

                        <div class="col-md-6">
                            <label for="video_id" class="form-label">Video Id</label>
                            <input type="text" class="form-control  @error('video_id') is-invalid @enderror"
                                name="video_id" value="{{ old('video_id', $video?->video_id) }}" placeholder="Video Id"
                                required>

                            <div class="invalid-feedback">
                                @error('video_id')
                                    {{ $message }}
                                @else
                                    {{ 'This field is required.' }}
                                @enderror
                            </div>

                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="title" class="form-label">Title (JSON)</label>
                            <textarea id="title" class="form-control  @error('title') is-invalid @enderror" name="title" rows="6">{{ old('title', $video?->title) }}</textarea>

                            <div class="invalid-feedback">
                                @error('title')
                                    {{ $message }}
                                @else
                                    {{ 'This field is required.' }}
                                @enderror
                            </div>

                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.topic-video.index', [$topic->id, 0]) }}" class="btn btn-primary btn-sm ms-1"
                        id="createBtn">
                        Create
                    </a>
                    <button type="button" class="btn btn-info btn-sm" id="reorderBtn"
                        onclick="sortData('{{ route('admin.topic-video.sort') }}', '{{ csrf_token() }}')">
                        Sort
                    </button>

                    <x-admin.table :headers="['#', 'Video ID', 'Title (JSON)', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Select2 height fix for Bootstrap 5 */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            /* Bootstrap rounded-md */
        }

        .select2-selection__rendered {
            line-height: 30px !important;
        }

        .select2-selection__arrow {
            height: 44px !important;
            top: 6px;
        }
    </style>

    <script>
        // Bootstrap 5 form validation
        (function() {
            'use strict';

            // Fetch all forms with class 'needs-validation'
            var forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission if invalid
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    // Add Bootstrap validation classes
                    form.classList.add('was-validated');
                }, false);
            });

        })();
    </script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.topic-video.dataTable') }}",
                    data: {
                        topic_id: "{{ $topic->id }}"
                    }
                },
                columns: [{
                        data: 'position',
                        name: 'position',
                    },
                    {
                        data: 'video_id',
                        name: 'video_id',
                        render: function(data, type, row, meta) {
                            return `<div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/${data}?autoplay=1&mute=0&&modestbranding=1&rel=0"</iframe>
                                </div>`;
                        }
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            const url =
                                "{{ route('admin.topic-video.index', [$topic->id, ':id']) }}"
                                .replace(':id', row.id);
                            const deleteUrl = "{{ route('admin.topic-video.destroy', [':id']) }}"
                                .replace(':id', row.id);

                            return `<a href="${url}" class="btn btn-link">Edit</a>|
                                <button class="btn btn-link text-danger" onclick="deleteItem('${deleteUrl}', '{{ csrf_token() }}')">Delete</button>`;
                        }
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
                rowId: 'id',
                drawCallback: function(settings) {
                    makeSortable();
                },
            });
        });
    </script>
@endpush
