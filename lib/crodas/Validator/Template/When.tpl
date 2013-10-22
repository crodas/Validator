{{ $args[0]->toCode($input, $self) }}

if ({{ $args[0]->result }}) {
    {{ $args[1]->toCode($input, $self) }}
    {{ $self->result }} = {{$args[1]->result}};
} else {
    {{ $args[2]->toCode($input, $self) }}
    {{ $self->result }} = {{$args[2]->result}};
}
