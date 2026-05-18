<x-guest-layout>
    <div class="mb-6 text-sm text-lime-700 leading-relaxed">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-sm text-lime-600 bg-lime-100 p-4 rounded-lg">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-lime-600 hover:text-lime-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 transition duration-150">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
