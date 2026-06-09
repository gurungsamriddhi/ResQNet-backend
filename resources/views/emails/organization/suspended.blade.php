<x-mail::message>
# Organization Account Suspended

Your organization account on ResQNet has been **suspended** for the following reason(s):

<x-mail::panel>
{!! nl2br(e($reason)) !!}
</x-mail::panel>

While suspended, you will not be able to access the platform or manage your listings. 
Please contact support for further information.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
