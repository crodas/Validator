{{$self->result}} = is_object({{$input}});
@if (!empty($args)) 
if ({{$self->result}}) {
    @foreach ($args as $i => $class)
        $type_{{$i}} = {{@$class}};
    @end
    $val = 
    @foreach ($args as $i => $class)
        ({{$input}} instanceof $type_{{$i}}) ||
    @end
        false;

    {{$self->result}} = $val;
}
@end

