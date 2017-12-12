@foreach($dnsrecords as $record)
<p>domain:{{$record->domain}}</p>
<p>ip:{{$record->ip}}</p>
<p>country_name:{{$record->country_name}}</p>
<p>region_name:{{$record->region_name}}</p>
<p>city:{{$record->city}}</p>
<p>latitude:{{$record->latitude}}</p>
<p>longitude:{{$record->longitude}}</p>
@endforeach



<form action = "inquire" method = "POST">
{!!csrf_field()!!}

<textarea name="quest" required="required" placeholder="write...."></textarea>
<button>submit</button>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</form>