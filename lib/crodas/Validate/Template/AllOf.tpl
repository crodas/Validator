{{$self->result}} = true;
@foreach ($args as $rule) 
    if ({{$self->result}}) {
        {{ $rule->toCode($input) }}
        if (!{{$rule->result}}) {
            {{$self->result}} = false;
            goto exit_{{sha1($self->result)}};
        }
    }
@end
exit_{{sha1($self->result)}}:
