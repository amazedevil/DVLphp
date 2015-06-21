# DVL php - data structures validation library

## About

This is a data structure validation library, based on DVL - data validation language. DVL is platform independent validation expression language. Best way to understand how to use it - look at tests file [ValidatorTest](tests/ValidatorTest.php), because it contains use cases with all library features used. Here we will look at one case + short reference for validation expressions and native functions.

Let's look at an example - we've got some data structure (from HTML form or something):

```php
array(
    'first_name' => 'Dexter',
    'last_name' => 'Holland',
    'age' => 49,
    'instrument' => 'microphone',
    'songs_written' => 'many',
    'professions' => array( 'musician', 'composer', 'singer' ),
    'band_id' => 'the_offspring'
)
```

We can validate it this way:

```php
$validator = new DVLValidator('{
    STRLEN(this.first_name) < 64 @ "First name must be no longer than 64 symbols",
    STRLEN(this.last_name) < 64 @ "Last name must be no longer than 64 symbols",
    (this.age) {
        this > 20 @ "Not enough old" % "age_young",
        this < 100 @ "Too old" % "age_old"
    },
    $(this.professions) {
        (value == 'composer') ?
            (this.songs_written) this > 0 || this == 'many' :
            true
    },
    BAND_EXISTS(this.band_id)
}', [
    'functions' => [
        'BAND_EXISTS' => function($id) {
            //Here we're making some DB request or something and returning boolean
        }
    ]
]);

$validator->validate(array(
    'first_name' => 'Dexter',
    'last_name' => 'Holland',
    'age' => 49,
    'instrument' => 'microphone',
    'songs_written' => 'many',
    'professions' => array( 'musician', 'composer', 'singer' ),
    'band_id' => 'the_offspring'
));
```

## Quick start

String we're passing to DVLValidator constructor contains validation expression. Validation expression executes on some [context](#context) and, as a result - passes, or fails.

##### Context

Context contains 'this' variable, which (without [context changing](#use)) contains passed validation structure. Also it can have some other variables, for example passed to it by validation expression [Foreach](#foreach) (see below).

Validation expression can have one of types:

- Expression
- Group
- Use
- Conditional
- Foreach

##### Expression

Boolean, numeric, string or array expression ("Expression"), every result but boolean 'false' is treated as passing, otherwise, or if result can't be calculated - expression fails. Expression can contain error message and tag. Error message can contain current context variable insertions in form of variable name enclosed in braces.

Boolean expression can be:

- Boolean constant (true or false)
- [Variable](#variables)
- [Function call](#functions)
- Binary logical operation: and (a && b), or (a || b), where a and b should be boolean expressions. And expression means that both a and b expressions should pass. Or expression means that a expression can fail, but in that case b expression should pass.
- Unary logical operation: not (!a), where a should be boolean expression.
- Comparison operation: equal (a == b), not equal (a != b), less (a < b), less or equal (a <= b), greater (a > b), greater or equal (a >= b). Less, Greater, Less or equal and Greater or equal operations can have only numeric operands, meanwhile equal and not equal operations can have operands of numeric, boolean and string type. Comparing values of different types causes expression failing.
- Any other boolean expression enclosed in parentheses.

Numeric expression can be:

- Numeric constant (number)
- [Variable](#variables)
- [Function call](#functions)
- Arithmetic binary operation - sum (a + b), difference (a - b), product (a * b), division (a / b), modulo (a % b), where a and b should be numeric expression.
- Arithmetic unary operation - inverse (-a), where a should be numeric
- Any other arithmetic expression enclosed in parentheses.

String expression can be:

- [Variable](#variables)
- [Function call](#functions)
- String constant - "test '\" test", or 'test \'" test' - these forms are equal.

Array expression can be:

- [Variable](#variables)
- [Function call](#functions)

Example:

```php
$validator = new DVLValidator('this > 0 @ "test error message this = {this}" % "test_error_tag"');
$validator->validate(3);
```

##### Group

Simply a group of validation expressions (at least one) of any kind enclosed by braces and separated by comma.
Form: 
```
{ [Validation expression], [Validation expression], ... }
```
Expressions executes strictly in given order and stop executing on the first fail (have plans to make it optional, see [TODO](#todo)).

Example:

```php
$validator = new DVLValidator('{
    this > 0,
    this < 30
}');
$validator->validate(3);
```

##### Use

Context changing expression.
Form: 
```
([Expression]) [Validation expression]
```
Expression in parenthesis will calculate new 'this', all other variables copied from parent context.

Example:

```php
$validator = new DVLValidator('(this.num) this > 0');
$validator->validate(array( 'num' => 3 ));
```

##### Conditional

Some kind of 'if', if you wish.
Form: 
```
([Expression]) ? [Validation expression] : [Validation expression]
```
if expression in parenthesis can be calculated and equals boolean 'true', first validation expression executes, otherwise - second.

Example:

```php
$validator = new DVLValidator('(IS_ARRAY(this)) ? COUNT(this) > 0 : this > 0');
$validator->validate(array( 1 ));
$validator->validate(1);
```

##### Foreach

Iterates through array and executes some validation. Adds key and value variables in context, with which enclosing validation expressions executed.
Form (all three are equal):
```
$(this : k => v) { STRING(k), v > 0 }
$(this : v) { STRING(key), v > 0 }
$(this) { STRING(key), value > 0 }
```

##### Variables

Variable is variable name followed by set of accessors (may be empty one).

Accessors:
- Property - example: 'this.name' - accessing associative array item value by key.
- Selector - form: 'variable[String expression|Numeric expression|Boolean expression]'. If it's string expression or numeric expression, it acts like property accessor. If it's boolean expression - it calculates value for every array item and if this value equals 'true', it includes this value in returning array. Expression context has variable 'i', which value contains item key value, or array index.

##### Functions

Native library functions:

- KEYS - returns array keys or indexes if argument is array, and fails otherwise
- INT - returns value of argument if it's integer, and fails otherwise
- STRING - returns value of argument if it's string, and fails otherwise
- ARRAY - returns value of argument if it's array, and fails otherwise
- BOOL - returns value of argument if it's boolean, and fails otherwise
- STRLEN - returns string length of argument if it's string, and fails otherwise
- IS_ASSOC - returns true if argument is associative array, and false otherwise
- IS_ARRAY - returns true if is array, but not assoicative one and false otherwise
- COUNT - returns number elements in argument if it's array, and fails otherwise
- NATIVE_REGEX_MATCH - equal to php preg_match function, with checking that first two arguments are strings and second two are integers, and failing otherwise.

Also you can declare your own functions. To fail custom function you should throw exception which parent is BaseValidationException.

Example:

```php
$validator = new DVLValidator('STARTS_WITH_N(this)', [
    'functions' => [
        'STARTS_WITH_N' => function($text) {
            return strpos($text, 'N') === 0;
        }
    ]
]);
```

## TODO

- Add array constants
- Add operation [Expression] 'in' [Array expression]
- Add option to not stop group validator on first fail
