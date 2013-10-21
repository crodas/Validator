if ({{$input}} instanceof \DateTime) {
    {{$self->result}} = true;
} elseif (!is_string({{$input}})) {
    {{$self->result}} = false;
} else {
    @if (empty($args[0])) 
        {{$self->result}} = false !== strtotime({{$input}});
    @else
        $dateFromFormat = \DateTime::createFromFormat({{@$args[0]}}, {{$input}});
        {{$self->result}} = $dateFromFormat
                && {{$input}} === date({{@$args[0]}}, $dateFromFormat->getTimestamp());
    @end
}
