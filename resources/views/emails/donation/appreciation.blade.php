<x-mail::message>
# Thank You, {{ $donorName }}!

Your generosity makes our mission possible. On behalf of the entire **ResQNet** community, we want to express our sincere gratitude for your heartfelt contribution.

<x-mail::panel>
**Donation Amount:** Rs. {{ number_format($amount, 2) }}  
**Date:** {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
</x-mail::panel>

Your support helps us maintain the platform, improve features, and ensure help reaches those who need it most. Every contribution brings us one step closer to a better, safer community for everyone.

<x-mail::button :url="url('/')" color="primary">
Visit ResQNet
</x-mail::button>

With gratitude,  
**The ResQNet Team**

---

<small style="color: #94a3b8;">This is an automated appreciation message. Please do not reply to this email.</small>
</x-mail::message>
