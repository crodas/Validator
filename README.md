Validator
=========

Generate static validators to validate your data in PHP. *Static* means that the rules are defined offline, then the library generates code to run the validations. We don't run any checking at runtime.

If you need to define validation rules on the fly, for instance you have a CMS or something like that, then this library would be of little help (unless you call the `crodas\Validator\Builder` manually), in that case I would recommend to take a look at @Respect's [validation library](https://github.com/respect/Validation) which has been an inspiration for this library.

How to installed
-------------

The easiest way of install the package is using Composer:

```
composer "crodas/validator":\*

```


How to use it
-------------

The first thing you have to do is create the Validator object.

```php
require "vendor/autoload.php";

$val = new crodas\Validator\Init("/classes/", "/tmp/foo.php");
```

The validator object would look for classes defined inside `/classes/` and its subdirectories the first time. It would then load classes with @Validate annotation in it.

All the validation rules are defined used Annotatios, it should be like this

```php
/** @Validate */
class User
{
    /** @Between([18, 99], "Invalid age range, it should be between 18-99") */ 
    protected $age;
    
    /** 
     * @NoWhitespace("Spaces are not allowed")
     * @MinLength([5], "{$value} is too short") 
     * @MaxLength([10], "{$value} is too long")
     */
    public $username;
}
```

To validate an object you would have to do something like this.

```
$errors = $val->validate(new User);
if (!empty($errors)) {
  foreach ($errors as $field => $exception) {
     echo "{$field} is not valid ( {$exception} )\n";
  }
}
```
