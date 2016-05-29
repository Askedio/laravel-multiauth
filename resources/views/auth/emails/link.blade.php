Here is your login link..

{{ url('/auth/email/', $oauth->token) }}

It expires {{ $oauth->expires_at->diffForHumans()}}