<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/email') }}">
    {{ csrf_field() }}
    <input type="hidden" value="1" name="remember">


    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <div class="col-sm-12">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="email@example.com">

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <div class="col-sm-12">
            <input type="password" class="form-control" name="password" placeholder="password">

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary btn-block">
                I'll remember this, I swear.
            </button>

            <a class="btn btn-link" href="{{ url('/password/reset') }}">Crap, I didn't remember it :(</a>
        </div>
    </div>
</form>