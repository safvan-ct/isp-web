@extends('layouts.admin')

@section('content')
    <x-admin.alert type="success" />
    <x-admin.alert type="error" />

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('admin.profile.update-profile-information')

    @include('admin.profile.update-password')

    @include('admin.profile.delete-user')
@endsection

@push('scripts')
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#deleteAccountModal').modal('show');
            });
        </script>
    @endif
@endpush
