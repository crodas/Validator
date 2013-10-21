<?php

@if ($namespace) 
namespace {{$namespace}};
@end

@foreach ($functions as $name => $body)
function {{$name}} ({{$var}})
{
    {{$body->toCode($var)}}
    return {{$body->result}};
}

@end

function validate($rule, $input)
{
    switch ($rule) {
        @foreach ($funcmap as $name => $func)
        case {{var_export($name, true)}}:
            $valid = {{$func}}($input);
            break;
        @end
        default:
            throw new \Exception("Cannot find validator for {$rule}");
    }
    return $valid;

}

@if (count($classes) > 0)
function get_object_properties($object)
{
    $class = strtolower(get_class($object));
    $data  = [];
    @foreach ($classes as $name => $props)
    switch ($class) {
    case {{var_export(strtolower($name), true)}}:
        @foreach($props as $name => $is_public)
            @if ($is_public)
                $data[{{var_export($name, true)}}] = $object->{{$name}};
            @else
                $property = new \ReflectionProperty($object, {{ var_export($name, true) }});
                $property->setAccessible(true);
                $data[{{var_export($name, true)}}] = $property->getValue($object);
            @end
        @end
        break;
    @end
    default:
        throw new \Exception("Cannot find a validations for {$class} object");
    }
    return $data;
}
@end