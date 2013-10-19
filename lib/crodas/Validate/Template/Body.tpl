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
        case "{{{$name}}}":
            $valid = {{$func}}($input);
            break;
        @end
        default:
            throw new \Exception("Cannot find validator for {$rule}");
    }
    return $valid;

}
