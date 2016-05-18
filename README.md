# Proof of concept: PHP Version Transpiler

Just a quick check if it is possible to parse php files and remove/change PHP7 features to create PHP 5.6 compatible code

No functionality, just a test suite.

Input: `tests/_fixtures`
Output: `tests/_out`

## Installation

requires composer & php7

````
composer install
bin/phpunit

````

