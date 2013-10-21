{{$self->result}} = false;
@foreach ($args as $rule) 
    {{ $rule->toCode($input, $self) }}
    if ({{$rule->result}}) {
        {{$self->result}} = true;
        goto exit_{{sha1($self->result)}};
    }
@end
exit_{{sha1($self->result)}}:
