<x-admin.card title="Profile Information" subTitle="Update your account's profile information and email address.">
    @if (session('status') === 'profile-updated')
        <x-admin.alert type="success" message="Profile updated successfully." />
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form action="{{ route('admin.profile.update') }}" method="post">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-4">
                <x-admin.input name="first_name" label="First Name" :value="old('first_name', $user->first_name)" placeholder="First Name" required
                    error="true" />
            </div>

            <div class="col-4">
                <x-admin.input name="last_name" label="Last Name" :value="old('last_name', $user->last_name)" placeholder="Last Name"
                    error="true" />
            </div>

            <div class="col-4">
                <x-admin.input type="email" name="email" label="Email" :value="old('email', $user->email)" placeholder="Email"
                    required error="true" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div>
                <p class="text-md mt-2">
                    Your email address is unverified.
                    <x-admin.button form="send-verification" class="btn btn-light">
                        Click here to re-send the verification email.
                    </x-admin.button>
                </p>

                @if (session('status') == 'verification-link-sent')
                    <x-admin.alert type="success"
                        message="A new verification link has been sent to your email address." />
                @endif
            </div>
        @endif

        <x-admin.button>SAVE</x-admin.button>
    </form>
</x-admin.card>
