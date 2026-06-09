<x-mail::message>
# Organization Registration Status

We have reviewed your organization registration for ResQNet.

Unfortunately, your registration was **not approved** for the following reason(s):

<x-mail::panel>
{!! nl2br(e($reason)) !!}
</x-mail::panel>

If you have any questions or would like to appeal this decision, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
