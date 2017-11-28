@foreach($dnsrecords as $record)
<p>inquire:</p>
<p>{{$record->domain}}</p>
<p>result:</p>
<p>cname:{{$record->cname}};      ip:[{{$record->ip}}];     city:{{$record->city}};  country:{{$record->country}}</p>

@endforeach

<form action = "inquire" method = "POST">
{!!csrf_field()!!}

<textarea name="body" required="required" placeholder="write...."></textarea>
<button>submit</button>
</form>