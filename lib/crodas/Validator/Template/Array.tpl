{{$self->result}} = is_array({{$input}}) || ({{$input}} instanceof \ArrayAccess
    && {{$input}} instanceof \Traversable
    && {{$input}} instanceof \Countable);
