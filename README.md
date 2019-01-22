# XArray
A utility for traversing PHP arrays with an XPath-like syntax.

[![travis-ci.org](https://travis-ci.org/MindTouch/XArray.php.svg?branch=master)](https://travis-ci.org/MindTouch/XArray.php)
[![codecov.io](https://codecov.io/github/MindTouch/XArray.php/coverage.svg?branch=master)](https://codecov.io/github/MindTouch/XArray.php?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mindtouch/xarray/version.svg)](https://packagist.org/packages/mindtouch/xarray)

## Requirements
* PHP 5.4, 5.5, 5.6 (0.1.x)
* PHP 7.2+ (master, 1.x)

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

### XArray
```php
// use XArray from scratch
$x1 = new XArray();

// set some values
$x1->setVal('foo/bar', 'baz');
$x1->setVal('qux', ['fred', 'quxx']);

// get some values
$result = $x1->getVal('foo/bar'); // baz
$result = $x1->getVal('qux'); // fred
$results = $x1->getAll('qux'); // ['fred', 'quxx']

// get the array
$array1 = $x1->toArray();

// create a new XArray from the existing array
$x2 = new XArray($array1);

// override a value and set a new value
$x2->setVal('foo/bar', ['qux', 'baz']);
$x2->setVal('bar', 'foo');

// get some values
$result = $x2->getVal('foo/bar'); // qux
$result = $x2->getAll('bar'); // ['foo']
$results = $x2->getAll('qux'); // ['fred', 'quxx']

// get a value strictly as a string
$x2->setVal('qwerty', true);
$x2->setVal('asdf', new class {
    public function __toString() {
        return 'zxcv';
    }
});
$result = $x2->getString('qwerty'); // 'true'
$result = $x2->getString('asdf'); // 'zxcv'

// get the new array
$array2 = $x2->toArray();

// XArray does not mutate the source array
assert($array1 !== $array2);

// get an XML representation of the array
$xml = $x2->toXml('mindtouch');
```
```xml
<foo>
    <bar>qux</bar>
    <bar>baz</bar>
</foo>
<qux>fred</qux>
<qux>quxx</qux>
<bar>foo</bar>
<querty>true</querty>
<asdf>zxcv</asdf>
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
$x = new MutableXArray($array1);

// set some values
$x->setVal('foo/bar', 'qux');
$x->setVal('fred', 'quxx');

// get the new array
$array2 = $x->toArray();

// MutableXArray mutates the source array
assert($array1 === $array2);
```
