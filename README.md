# XArray

A utility for traversing PHP arrays with an XPath-like syntax.

[![travis-ci.org](https://travis-ci.org/modethirteen/XArray.php.svg?branch=master)](https://travis-ci.org/modethirteen/XArray.php)
[![codecov.io](https://codecov.io/github/modethirteen/XArray.php/coverage.svg?branch=master)](https://codecov.io/github/modethirteen/XArray.php?branch=master)
[![Latest Stable Version](https://poser.pugx.org/modethirteen/xarray/version.svg)](https://packagist.org/packages/modethirteen/xarray)
[![Latest Unstable Version](https://poser.pugx.org/modethirteen/xarray/v/unstable)](https://packagist.org/packages/modethirteen/xarray)

## Requirements

* PHP 5.4, 5.5, 5.6 (0.1.x)
* PHP 7.2, 7.3 (php72, 1.x)
* PHP 7.4+ (master, 2.x)

## Installation

Use [Composer](https://getcomposer.org/). There are two ways to add XArray to your project.

From the composer CLI:

```sh
./composer.phar require modethirteen/xarray
```

Or add modethirteen/xarray to your project's composer.json:

```json
{
    "require": {
        "modethirteen/xarray": "dev-master"
    }
}
```

`dev-master` is the master development branch. If you are using XArray in a production environment, it is advised that you use a stable release.

Assuming you have setup Composer's autoloader, XArray can be found in the `modethirteen\XArray\` namespace.

## Usage

### XArray.php

```php
// use XArray from scratch
$x1 = new XArray();

// set some values
$x1->setVal('foo/bar', 'baz');
$x1->setVal('qux', ['fred', 'quxx']);

// get some values
$result = $x1->getVal('foo/bar'); // 'baz'
$result = $x1->getVal('qux'); // 'fred'
$results = $x1->getAll('qux'); // ['fred', 'quxx']

// get the array
$array1 = $x1->toArray();

// create a new XArray from the existing array
$x2 = new XArray($array1);

// override a value and set a new value
$x2->setVal('foo/bar', ['qux', 'baz']);
$x2->setVal('bar', 'foo');

// get some values
$result = $x2->getVal('foo/bar'); // 'qux'
$result = $x2->getAll('bar'); // ['foo']
$results = $x2->getAll('qux'); // ['fred', 'quxx']

// we can get a value strictly as a string, if we are in strict typing mode!
// first we set some non-string values...
$x2->setVal('qwerty', true);
$x2->setVal('asdf', new class {
    public function __toString() {
        return 'zxcv';
    }
});

// ...and cast them to string!
$result = $x2->getString('qwerty'); // 'true'
$result = $x2->getString('asdf'); // 'zxcv'

// of course, string values themselves can be fetched as strict string types
$result = $x2->getString('foo/bar'); // 'qux'

// get the new array
$array2 = $x2->toArray();

// XArray does not mutate the source array
assert($array1 !== $array2);

// get an XML representation of the array
$xml = $x2->toXml('xyzzy');
```

```xml
<xyzzy>
    <foo>
        <bar>qux</bar>
        <bar>baz</bar>
    </foo>
    <qux>fred</qux>
    <qux>quxx</qux>
    <bar>foo</bar>
    <querty>true</querty>
    <asdf>zxcv</asdf>
</xyzzy>
```

### MutableXArray.php (extends XArray.php)

```php
// MutableXArray always requires a source array
$array1 = [
  'foo' => [
      'bar',
      'baz'
  ]
];
$x = new MutableXArray($array1);

// set some values
$x->setVal('foo/bar', 'qux');
$x->setVal('fred', 'quxx');

// get the new array
$array2 = $x->toArray();

// MutableXArray mutates the source array
assert($array1 === $array2);
```
