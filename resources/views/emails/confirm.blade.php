@component('mail::message')
# Hi {{ $user->name }}, One Last Step

We just need you to confirm your email address to prove that you are not a robot.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
