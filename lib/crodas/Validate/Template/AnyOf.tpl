{{$self->result}} = false;
@foreach ($args as $rule) 
    {{ $rule->toCode($input) }}
    if ({{$rule->result}}) {
        {{$self->result}} = true;
        exit_{{sha1($self->result)}};
    }
@end
exit_{{sha1($self->result)}}:
