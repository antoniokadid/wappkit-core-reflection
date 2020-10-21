# WAPPKit Core - Reflection
A PHP library that provides functionality using reflection.

Part of Web Application Kit (WAPPKit) Core which powers WAPPKit, a privately owned CMS.

*Project under development and may be subject to a lot of changes. Use at your own risk.*

## Installation

composer require antoniokadid/wappkit-core-reflection

## Requirements

* PHP 7.1 or above.

## Examples


```php
use AntonioKadid\WAPPKitCore\Reflection\ClosureInvoker;

/* 
Invoke a closure by passing an array of key-value pairs.
- The order of items doesn't matter as long as the keys have the same names as the parameters of the closure.
- The invoker will automatically try to convert the values to the expected format.
*/

$invoker = new ClosureInvoker(
            function (int $count, string $value): string {
                return sprintf('%s %d.', $value, $count);
            });

$result = $invoker->invoke(['value' => 'The number is', 'count' => '10500']);
echo $result;
```
Output:
```
The number is 10500.
```