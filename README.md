# Proof of concept: PHP Version Transpiler

Just a quick check if it is possible to parse php files and remove/change PHP7 features to create PHP 5.6 compatible code

No exposed functionality, just a test suite.

Input: `tests/_fixtures`
Output: `tests/_out`

## Installation

requires composer & php7

````
composer install
bin/phpunit

````

## Support

As with every solution to such a problem there is no way to solve erverything, and there is no way to solve everything with one thing.
I suspect there will far more use cases for a Shim for many incompatibility between 5.6 and 7.0. Other stuff will be that hard to detect, that I am not sure it will be worth it.
Especially some of the more subtle additions to the language will not be emaulatable. But maybe they are not worth pursuing either?

#### From http://php.net/manual/de/migration70.new-features.php

| Feature                             | Supported     | Emulated   | Possible | SHIM     | Notes 
| ----------------------------------- |:-------------:| :---------:| :-------:| :-------:| :-------
| Scalar type declarations            | x             | -          | x        | -        |  
| Return type declarations            | x             | -          | x        | -        |
| Null coalescing operator            | x             | x          | x        | -        |
| Spaceship operator                  | -             | -          | ?        | -        |
| Constant arrays using define()      | -             | -          | x        | -        |
| Anonymous classes                   | x             | x          | x        | -        | But get_class() will now give something real back
| Unicode codepoint escape syntax     | -             | -          | partly?  | partly?  | Would need a shim
| Closure::call()                     | -             | -          | x        | -        |
| Filtered unserialize()              | -             | -          | x        | -        | Generally a very hard implementation for such a simple feature
| IntlChar                            | -             | -          | -        | x        |
| Expectations                        | -             | -          | -        | -        | It is backwards compatible
| Group use declarations              | -             | -          | x        | -        |
| Generator Return Expressions        | -             | -          | partly?  | partly?  | Would need a shim
| Generator delegation                | -             | -          | -        | -        |
| Integer division with intdiv()      | -             | -          | -        | x        | Should be much easier to do this by Shim 
| Session options                     | -             | -          | ?        | -        | maybe, but might be leaky
| preg_replace_callback_array()       | -             | -          | x        | -        | Can be very easily a Shim
| CSPRNG Functions                    | -             | -          | .        | -        | Can be very easily a Shim

#### From http://php.net/manual/de/migration70.incompatible.php

| Feature                                                                                            | Supported     | Emulated   | Possible | SHIM     | Notes 
| -------------------------------------------------------------------------------------------------- |:-------------:| :---------:| :-------:| :-------:| :-------
| Changes to error and exception handling                                                            | -             | -          | x        | -        | The docs contain a way out  
| Changes to the handling of indirect variables, properties, and methods                             | -             | -          | x        | -        |
| Changes to list() handling                                                                         | -             | -          | ?        | -        |
| Array ordering when elements are automatically created during by reference assignments has changed | -             | -          | -        | -        | And never will be....
| Parentheses around function parameters no longer affect behaviour                                  | -             | -          | x        | -        | Can be removed automatically
| foreach no longer changes the internal array pointer                                               | -             | -          | -        | -        | Therefore current() is essentially rendered useless here
| foreach by-value operates on a copy of the array                                                   | -             | -          | partly?  | partly?  | Would need support from a shim, might be hard to detect 
| foreach by-reference has improved iteration behaviour                                              | -             | -          | partly?  | partly?  | Would need support from a shim, might be hard to detect
