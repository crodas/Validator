{{ $self->result }} = true;
if (empty({{$input}})) {
    @if (!empty($parent))
        goto exit_{{sha1($parent->result)}};
    @else 
        return true;
    @end
}
