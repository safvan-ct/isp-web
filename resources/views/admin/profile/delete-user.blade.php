<x-admin.card title="Delete Account"
    subTitle="Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.">
    <x-admin.button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">DELETE
        ACCOUNT</x-admin.button>
</x-admin.card>

<x-admin.modal id="deleteAccountModal" title="Are you sure you want to delete your account?">
    <form action="{{ route('admin.profile.destroy') }}" method="post">
        @csrf
        @method('delete')

        <p class="mt-1 text-md">
            Once your account is deleted, all of its resources and data will be permanently deleted. Please
            enter your password to confirm you would like to permanently delete your account.
        </p>

        <div class="row">
            <div class="col-12">
                <x-admin.input type="password" name="password" label="Password" error="0" placeholder="Password"
                    required autocomplete="new-password" />

                @if ($errors->userDeletion->has('password'))
                    <p class="text-danger">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>
        </div>

        <x-admin.button class="btn btn-danger btn-sm">Delete</x-admin.button>
        <x-admin.button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</x-admin.button>
    </form>
</x-admin.modal>
