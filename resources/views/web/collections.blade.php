@extends('layouts.web')

@section('title', __('app.bookmarks'))

@section('content')
    <x-web.page-header :title="'<i class=\'bi bi-bookmark-heart-fill text-white me-2\'></i>' . e(__('app.my_collections'))" />

    <x-web.container class="notranslate">
        <div class="row g-4">
            @forelse ($collections as $collection)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 border-2 shadow">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-bookmark-fill text-primary me-2"></i>
                                <h5 class="mb-0 text-truncate text-capitalize">{{ $collection->name }}</h5>
                                <span class="badge bg-primary ms-auto">
                                    {{ $collection->items_count }}
                                    {{ Str::plural('Item', $collection->items_count) }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-auto">
                                <div class="d-flex gap-2">
                                    <!-- Edit button triggers modal -->
                                    <button class="btn p-0 border-0 bg-transparent text-warning edit-btn" title="Edit"
                                        data-id="{{ $collection->id }}" data-name="{{ $collection->name }}">
                                        <i class="bi bi-pencil-square text-info fs-5"></i>
                                    </button>

                                    <form action="{{ route('collections.destroy', $collection->id) }}" method="POST"
                                        onsubmit="return confirmDelete(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent text-danger"
                                            title="Delete">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>

                                <a href="{{ route('collections.show', $collection->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted text-primary">{{ __('app.no_collections_found') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $collections->links('pagination::bootstrap-5') }}
        </div>
    </x-web.container>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCollectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="editCollectionForm">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Collection</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" name="name" id="collectionName" required>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            let editModal = new bootstrap.Modal(document.getElementById('editCollectionModal'));

            $(".edit-btn").on("click", function() {
                let id = $(this).data("id");
                let name = $(this).data("name");

                let url = "{{ route('collections.update', ':id') }}".replace(':id', id);
                $("#collectionName").val(name);
                $("#editCollectionForm").attr("action", url);

                editModal.show();
            });
        });

        function confirmDelete(form) {
            if (confirm('Are you sure?')) {
                $('#pageLoader').removeClass('d-none');
                return true;
            }

            return false;
        }
    </script>
@endpush
