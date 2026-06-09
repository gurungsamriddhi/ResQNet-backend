<x-mail::message>
# Organization Approved!

Congratulations, your organization has been approved by ResQNet administrators.

You can now access all the features provided using the email and password you used while registering.

<x-mail::button :url="route('organization.login')">
Login to Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
