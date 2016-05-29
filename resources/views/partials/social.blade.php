<ul class="list-inline" style="font-size: 48px">
    @foreach(config('services') as $type => $service)
        @if(isset($service['client_id']))
            <li><a href="{{ url('/auth/'. $type) }}"><i class="fa fa-{{ $type }}"></i></a></li>
        @endif
    @endforeach
</ul>