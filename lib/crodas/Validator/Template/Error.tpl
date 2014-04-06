@if ($self->msg)
if (!{{$self->result}}) {
        throw new \UnexpectedValueException({{$self->getErrorMessage()}});
}
@end
