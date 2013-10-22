{{$self->result}} = false;
if (is_scalar({{$input}})) {
    $expected = {{@$args}};
    $encoding = mb_detect_encoding({{$input}}, $expected, true);
    {{$self->result}} = in_array($encoding, $expected, true);
}
