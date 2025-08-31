<x-admin.card title="Update Password" subTitle="Ensure your account is using a long, random password to stay secure.">
    @if (session('status') === 'password-updated')
        <x-admin.alert type="success" message="Password updated successfully." />
    @endif

    <form action="{{ route('password.update') }}" method="post">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-6">
                <x-admin.input type="password" name="current_password" label="Current Password" error="0"
                    placeholder="Current Password" required autocomplete="current-password" />

                @if ($errors->updatePassword->has('current_password'))
                    <p class="text-danger">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div class="col-6">
                <x-admin.input type="password" name="password" label="New Password" error="0"
                    placeholder="New Password" required autocomplete="new-password" />

                @if ($errors->updatePassword->has('password'))
                    <p class="text-danger">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div class="mb-3 col-6">
                <x-admin.input type="password" name="password_confirmation" label="Confirm Password" error="0"
                    placeholder="Confirm Password" required autocomplete="new-password" />

                @if ($errors->updatePassword->has('password_confirmation'))
                    <p class="text-danger">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>
        </div>

        <x-admin.button>SAVE</x-admin.button>
    </form>
</x-admin.card>
