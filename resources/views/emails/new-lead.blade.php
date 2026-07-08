<x-mail::message>
# New lead from the website

**Name:** {{ $lead->name }}
**Email:** {{ $lead->email }}
**Company:** {{ $lead->company ?? '—' }}
**Phone:** {{ $lead->phone ?? '—' }}
**Need:** {{ $lead->need ?? '—' }}
**Locale:** {{ $lead->locale }}

**Message:**
{{ $lead->message ?? '—' }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
