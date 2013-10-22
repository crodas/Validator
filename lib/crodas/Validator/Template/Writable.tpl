if ({{$input}} instanceof \SplFileInfo) {
    {{$self->result}} = {{$input}}->isWritable();
} else {
    {{$self->result}} = (is_string({{$input}}) && is_writable({{$input}}));
}
