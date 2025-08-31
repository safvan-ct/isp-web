@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Translations" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('admin.dashboard')],
        ['label' => convertAsTitle($type), 'link' => route('admin.topics.index', $type)],
        ['label' => $topic->slug],
    ]" />

    <div class="row">
        <div class="col-sm-12">
            <x-admin.alert type="success" />
            <x-admin.alert type="error" />

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @php
                        $url = $translation?->id
                            ? route('admin.topic-translations.update', $translation->id)
                            : route('admin.topic-translations.store');

                        $method = $translation?->id ? 'PUT' : 'POST';
                    @endphp

                    <form class="row g-3 needs-validation" novalidate action="{{ $url }}" method="POST">
                        @csrf
                        @method($method)

                        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                        <input type="hidden" name="type" value="{{ $type }}">

                        <div class="col-md-4">
                            <label for="lang" class="form-label">Select language</label>
                            <select class="form-select @error('lang') is-invalid @enderror" required
                                aria-label="select language" name="lang" required>
                                <option value="">Select language</option>

                                @foreach (config('app.languages') as $key => $lang)
                                    <option value="{{ $key }}"
                                        {{ old('lang', $translation?->lang) == $key ? 'selected' : '' }}>
                                        {{ $lang }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback">
                                @error('lang')
                                    {{ $message }}
                                @else
                                    {{ 'This field is required.' }}
                                @enderror
                            </div>

                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control  @error('title') is-invalid @enderror" name="title"
                                value="{{ old('title', $translation?->title) }}" placeholder="Title" required>

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

                        @if (in_array($type, ['module', 'answer']))
                            @if (in_array($type, ['module']))
                                <div class="col-md-12">
                                    <label for="sub_title" class="form-label">Sub Title</label>
                                    <textarea id="sub_title" class="form-control  @error('title') is-invalid @enderror" name="sub_title">{{ old('sub_title', $translation?->sub_title) }}</textarea>

                                    <div class="invalid-feedback">
                                        @error('sub_title')
                                            {{ $message }}
                                        @else
                                            {{ 'This field is required.' }}
                                        @enderror
                                    </div>

                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <label for="content" class="form-label">Content</label>
                                <textarea id="content" name="content">{{ old('content', $translation?->content) }}</textarea>

                                <div class="invalid-feedback">
                                    @error('content')
                                        {{ $message }}
                                    @else
                                        {{ 'This field is required.' }}
                                    @enderror
                                </div>

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        @endif

                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @can('create category')
                        <a href="{{ route('admin.topic-translations.index', [$type, $topic->id]) }}"
                            class="btn btn-primary btn-sm ms-1" id="createBtn">
                            Create
                        </a>
                    @endcan

                    <x-admin.table :headers="['#', 'Language', 'Title', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/96omwjbp5wm2n3i93fqos4x6vvwtem6qkb5krg5i87r2yi21/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>

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
            tinymce.init({
                selector: '#content',
                plugins: [
                    // Core editing features
                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists',
                    'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                    // Your account includes a free trial of TinyMCE premium features
                    // Try the most popular premium features until Aug 19, 2025:
                    'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed',
                    'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable',
                    'advcode', 'advtemplate', 'ai', 'uploadcare', 'mentions', 'tinycomments',
                    'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography',
                    'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [{
                        value: 'First.Name',
                        title: 'First Name'
                    },
                    {
                        value: 'Email',
                        title: 'Email'
                    },
                ],
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                    'See docs to implement AI Assistant')),
                uploadcare_public_key: 'edfaf19a32ffce374d32',
            });

            $('#dataTable').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.topic-translations.dataTable') }}",
                    data: {
                        topic_id: "{{ $topic->id }}"
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
                        data: 'lang',
                        name: 'lang'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url =
                                "{{ route('admin.topic-translations.status', ':id') }}"
                                .replace(':id', row.id);

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
                                "{{ route('admin.topic-translations.index', [$type, $topic->id, ':id']) }}"
                                .replace(':id', row.id);
                            return `<a href="${url}" class="btn btn-link">Edit</button>`;
                        }
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }]
            });
        })
    </script>
@endpush
