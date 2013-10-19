{{$self->result}} = false;
@foreach ($args as $rule) 
    if (!{{$self->result}}) {
        {{ $rule->toCode($input) }}
        if ({{$rule->result}}) {
            {{$self->result}} = true;
            exit_{{sha1($self->result)}};
        }
    }
@end
exit_{{sha1($self->result)}}:
