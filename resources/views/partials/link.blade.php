<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/link') }}">
    {!! csrf_field() !!}
    <input type="hidden" value="1" name="remember">

    <div class="form-group{{ $errors->has('email_auth') ? ' has-error' : '' }}">
      <div class="col-sm-12">
            <input type="text" class="form-control" name="email_auth" value="{{ old('email_auth') }}" placeholder="email@example.com">

            @if ($errors->has('email_auth'))
                <span class="help-block">
                    <strong>{{ $errors->first('email_auth') }}</strong>
                </span>
            @endif
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">
        I'm hip, I know how to use e-mail.
    </button>
</form>