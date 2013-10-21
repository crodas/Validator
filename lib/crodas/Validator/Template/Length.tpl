$len = strlen({{$input}});
{{$self->result}} = $len >= {{$args[0]}} && $len <= {{$args[1]}};
