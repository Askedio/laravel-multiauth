@extends('layouts.app')

@section('content')
  <div class="container  text-center">
    @if ($errors->has('emailed'))
        <div class="col-sm-6 col-sm-offset-3">
            <div class="alert alert-warning">
                <h3>Check your email!</h3>
                There you will find the link you need to login to this site.

                @if(app()->environment('local'))
                  <p><a href="{{ url('/auth/link/callback/'.$errors->first('emailed')) }}">Local Testing: {{ $errors->first('emailed') }}</a></p>
                @endif
                <br><br>
            </div>
        </div>
    @else
      <h2>Login or Register in one place.</h2>
      <p class="small">You can delete your account after.</p>

      @if ($errors->has('authentication'))
          <div class="col-sm-6 col-sm-offset-3">
              <div class="alert alert-danger">
                  {{ $errors->first('authentication') }}
              </div>
          </div>
      @endif

      <div class="col-sm-4 col-sm-offset-4">
          <hr>
          <h3>Use a social network</h3>
          @include('partials.social')

          <hr>
          <h3>Get a login link</h3>
          @include('partials.link')

          <hr>
          <h3>Use email &amp; password</h3>
          @include('partials.password')

          @if(config("services.facebook.client_id"))
              <hr>
              <h3>Use an API</h3>
              @include('partials.facebook')
          @endif
      </div>
    @endif
  </div>
@endsection