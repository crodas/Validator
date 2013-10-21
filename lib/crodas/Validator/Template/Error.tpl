@if ($self->msg)
if (!{{$self->result}}) {
        throw new \UnexpectedValueException(_("{{{$self->msg}}}"));
}
@end
