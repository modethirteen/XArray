# XArray
A utility for traversing PHP arrays with an XPath-like syntax.

[![travis-ci.org](https://travis-ci.org/MindTouch/XArray.php.svg?branch=master)](https://travis-ci.org/MindTouch/XArray.php)
[![codecov.io](https://codecov.io/github/MindTouch/XArray.php/coverage.svg?branch=master)](https://codecov.io/github/MindTouch/XArray.php?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mindtouch/xarray/version.svg)](https://packagist.org/packages/mindtouch/xarray)

## Requirements
* PHP 5.4+

## Installation
Use [Composer](https://getcomposer.org/). There are two ways to add XArray to your project.

From the composer CLI:
```sh
$ ./composer.phar require mindtouch/xarray
```

Or add mindtouch/xarray to your project's composer.json:
```json
{
    "require": {
        "mindtouch/xarray": "dev-master"
    }
}
```
"dev-master" is the master development branch. If you are using XArray in a production environment, it is advised that you use a stable release.

Assuming you have setup Composer's autoloader, XArray can be found in the MindTouch\XArray\ namespace.

## Usage

### Methods
```php
/**
 * Set or replace a key value.
 *
 * @param string $key
 * @param string|array $value
 */
public function setVal($key, $value = null);

/**
 * Find $key in the XArray, which is delimited by /
 * If the found value is itself an array of multiple values, it will return the value of key '0'.
 *
 * @param string $key - the array path to return, i.e. /pages/content
 * @param mixed $default - if the key is not found, this value will be returned
 * @return mixed|null
 */
public function getVal($key = '', $default = null);

/**
 * Find $key in the XArray, which is delimited by /
 * If the found value is itself an array of multiple values, the array is returned.
 * If the found value is a single value, it is wrapped in an array then returned.
 *
 * @param string $key - the array path to return, i.e. /pages/content
 * @param mixed $default - if the key is not found, this value will be returned
 * @return array|mixed|null
 */
public function getAll($key = '', $default = []);

/**
 * Return the array as an XML string
 *
 * @param string $outer - optional output tag, used for recursion
 * @return string - xml representation of the array
 */
public function toXml($outer = null);

/**
 * Accessor for the array
 *
 * @return array
 */
public function toArray();
```

### XArray
```php
// use XArray from scratch
$X1 = new XArray();

// set some values
$X1->setVal('foo/bar', 'baz');
$X1->setVal('qux', ['fred', 'quxx']);

// get some values
$result = $X1->getVal('foo/bar'); // baz
$result = $X1->getVal('qux'); // fred
$results = $X1->getAll('qux'); // ['fred', 'quxx']

// NOTE: getVal

// get the array
$array1 = $X1->toArray();

// create a new XArray from the existing array
$X2 = new XArray($array1);

// override a value and set a new value
$X2->setVal('foo/bar', ['qux', 'baz']);
$X2->setVal('bar', 'foo');

// get some values
$result = $X2->getVal('foo/bar'); // qux
$result = $X2->getAll('bar'); // ['foo']
$results = $X2->getAll('qux'); // ['fred', 'quxx']

// get the new array
$array2 = $X2->toArray();

// XArray does not mutate the source array
assert($array1 !== $array2);

// get an XML representation of the array
$xml = $X2->toXml('mindtouch');
```
```xml
<foo>
    <bar>qux</bar>
    <bar>baz</bar>
</foo>
<qux>fred</qux>
<qux>quxx</qux>
<bar>foo</bar>
```

### MutableXArray (extends XArray)
```php
// MutableXArray always requires a source array
$array1 = [
  'foo' => [
      'bar',
      'baz'
  ]
];
$X = new MutableXArray($array1);

// set some values
$X->setVal('foo/bar', 'qux');
$X->setVal('fred', 'quxx');

// get the new array
$array2 = $X->toArray();

// MutableXArray mutates the source array
assert($array1 === $array2);
```
