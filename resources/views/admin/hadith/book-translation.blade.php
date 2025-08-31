@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Translations" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('admin.dashboard')],
        ['label' => 'Books', 'link' => route('admin.hadith-books.index')],
        ['label' => $book->name],
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
                            ? route('admin.hadith-book-translations.update', $translation->id)
                            : route('admin.hadith-book-translations.store');

                        $method = $translation?->id ? 'PUT' : 'POST';
                    @endphp

                    <form class="row g-3 needs-validation" novalidate action="{{ $url }}" method="POST">
                        @csrf
                        @method($method)

                        <input type="hidden" name="hadith_book_id" value="{{ $book->id }}">

                        <div class="col-md-4">
                            <label for="lang" class="form-label">Select language</label>
                            <select class="form-select @error('lang') is-invalid @enderror" required
                                aria-label="select language" name="lang" required>
                                <option value="">Select language</option>

                                @foreach (config('app.languages') as $key => $lang)
                                    @continue($key == 'ar')

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

                        <div class="col-md-4">
                            <label for="name" class="form-label">Book name</label>
                            <input type="text" class="form-control  @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $translation?->name) }}" placeholder="Name" required>

                            <div class="invalid-feedback">
                                @error('name')
                                    {{ $message }}
                                @else
                                    {{ 'This field is required.' }}
                                @enderror
                            </div>

                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="writer" class="form-label">Writer</label>
                            <input type="text" class="form-control  @error('writer') is-invalid @enderror" name="writer"
                                value="{{ old('writer', $translation?->writer) }}" placeholder="Writer" required>

                            <div class="invalid-feedback">
                                @error('writer')
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
                        <a href="{{ route('admin.hadith-book-translations.index', [$book->id]) }}"
                            class="btn btn-primary btn-sm ms-1" id="createBtn">
                            Create
                        </a>
                    @endcan

                    <x-admin.table :headers="['#', 'Language', 'Name', 'Writer', 'Status', 'Actions']"></x-admin.table>
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
                    url: "{{ route('admin.hadith-book-translations.dataTable') }}",
                    data: {
                        book_id: "{{ $book->id }}"
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'writer',
                        name: 'writer'
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
                                "{{ route('admin.hadith-book-translations.status', ':id') }}"
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
                                "{{ route('admin.hadith-book-translations.index', [$book->id, ':id']) }}"
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
