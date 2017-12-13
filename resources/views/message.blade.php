@foreach($messages as $message)
<p>{{$message->ip}}  {{$message->city}}<p>
<p>{{$message->body}}<p>

@endforeach

<form action = "message" method = "POST">
{!!csrf_field()!!}

<textarea name="body" required="required" placeholder="write...."></textarea>
<button>submit</button>
</form>