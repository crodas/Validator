<?php

@if ($namespace) 
namespace {{$namespace}};
@else
namespace crodas\Validator\ns{{uniqid(true)}};
@end

if (!is_callable('_')) {
    // No gettext? That's weird
    // but no problem mate!
    function _($a) 
    {
        return $a;
    }
}

@foreach ($functions as $name => $body)
function {{$name}} ({{$var}})
{
    $is_scalar = is_scalar({{$var}});
    {{$body->toCode($var)}}
    return {{$body->result}};
}

@end

function validate($rule, $input)
{
    switch ($rule) {
        @foreach ($funcmap as $name => $func)
        case {{@$name}}:
            $valid = {{$func}}($input);
            break;
        @end
        default:
            $valid = true;
    }
    return $valid;

}

@if (count($classes) > 0)
function get_object_properties($object)
{
    $class = strtolower(get_class($object));
    $data  = [];
    switch ($class) {
    @foreach ($classes as $name => $props)
    case {{@strtolower($name)}}:
        @foreach($props as $name => $is_public)
            @if ($is_public)
                $data[{{@$name}}] = $object->{{$name}};
            @else
                $property = new \ReflectionProperty($object, {{ @$name }});
                $property->setAccessible(true);
                $data[{{@$name}}] = $property->getValue($object);
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

return array(
    'mcallback' => __NAMESPACE__ . '\get_object_properties',
    'callback' => __NAMESPACE__ . '\validate',
);
