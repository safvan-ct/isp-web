@extends('layouts.admin')

@section('content')
    <x-admin.page-header :title="'Translations'" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('admin.dashboard')],
        ['label' => 'Hadith Verses', 'link' => route('admin.hadith-verses.index')],
        ['label' => 'Translations'],
    ]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
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

                    @php
                        $url = $translation?->id
                            ? route('admin.hadith-verse-translations.update', $translation->id)
                            : route('admin.hadith-verse-translations.store');

                        $method = $translation?->id ? 'PUT' : 'POST';
                    @endphp

                    <form class="row g-3 needs-validation" novalidate action="{{ $url }}" method="POST">
                        <input type="hidden" name="hadith_verse_id" value="{{ $verse->id }}">

                        @csrf
                        @method($method)

                        <div class="col-md-3">
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

                        @if ($verse->heading)
                            <div class="col-md-9">
                                <label for="heading" class="form-label">Heading</label>
                                <input type="text" class="form-control  @error('heading') is-invalid @enderror"
                                    name="heading" value="{{ old('heading', $translation?->heading) }}"
                                    placeholder="Heading" required>

                                <div class="invalid-feedback">
                                    @error('heading')
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
                            <label for="text" class="form-label">Hadith</label>
                            <textarea rows="8" name="text" class="form-control @error('text') is-invalid @enderror" required>{{ old('text', $translation?->text) }}</textarea>
                            <div class="invalid-feedback">
                                @error('text')
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
                    @can('create category')
                        <a href="{{ route('admin.hadith-verse-translations.index', [$verse->id]) }}"
                            class="btn btn-primary btn-sm ms-1" id="createBtn">
                            Create
                        </a>
                    @endcan

                    <x-admin.table :headers="['#', 'Language', 'Heading', 'Hadith', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
                    url: "{{ route('admin.hadith-verse-translations.dataTable') }}",
                    data: {
                        verse_id: "{{ $verse->id }}"
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
                        data: 'heading',
                        name: 'heading'
                    },
                    {
                        data: 'text',
                        name: 'text'
                    },
                    {
                        data: 'is_active',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            let label = data ? 'Active' : 'Inactive';
                            let text = data ? 'text-success' : 'text-danger';
                            let url =
                                "{{ route('admin.hadith-verse-translations.status', ':id') }}"
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
                                "{{ route('admin.hadith-verse-translations.index', [$verse->id, ':id']) }}"
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
