@if ($scalar)
    if ($is_scalar) {
        @include($type)
    } else {
        {{$self->result}} = false;
    } 
@else
    @include($type)
@end
