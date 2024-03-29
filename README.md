# ❎ Array

A utility for traversing PHP arrays with an XPath-like syntax.

[![github.com](https://github.com/modethirteen/XArray/workflows/build/badge.svg)](https://github.com/modethirteen/XArray/actions?query=workflow%3Abuild)
[![codecov.io](https://codecov.io/github/modethirteen/XArray/coverage.svg?branch=main)](https://codecov.io/github/modethirteen/XArray?branch=main)
[![Latest Stable Version](https://poser.pugx.org/modethirteen/xarray/version.svg)](https://packagist.org/packages/modethirteen/xarray)
[![Latest Unstable Version](https://poser.pugx.org/modethirteen/xarray/v/unstable)](https://packagist.org/packages/modethirteen/xarray)

## Requirements

* PHP 7.4 (main, 2.x)

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
        "modethirteen/xarray": "dev-main"
    }
}
```

`dev-main` is the main development branch. If you are using XArray in a production environment, it is advised that you use a stable release.

Assuming you have setup Composer's autoloader, XArray can be found in the `modethirteen\XArray\` namespace.

## Types

### XArray

```php
// use XArray from scratch
$x1 = new XArray();

// set some values
$x1->setVal('foo/bar', 'baz');
$x1->setVal('qux', ['fred', 'quxx']);

// get some values
$result = $x1->getVal('foo/bar'); // 'baz'
$result = $x1->getVal('qux'); // ['fred', 'quxx']

// which key paths have been defined in the XArray?
$keys = $x1->getKeys(); // ['foo', 'foo/bar', 'qux']

// reduce output to the key paths that have values
$flattened = $x1->toFlattenedArray(); // ['foo/bar' => 'baz', 'qux' => ['fred', 'quxx']]

// get the array (the underlying array data structure)
$array1 = $x1->toArray();

// create a new XArray from the existing array
$x2 = new XArray($array1);

// override a value and set a new value
$x2->setVal('foo/bar', ['qux', 'baz']);
$x2->setVal('bar', 'foo');

// get some values
$result = $x2->getVal('foo/bar'); // ['qux', 'baz']
$result = $x2->getVal('bar'); // 'foo'

// we can get a value strictly as a string, if we are in strict typing mode!
// first we set some non-string values...
$x2->setVal('qwerty', true);
$x2->setVal('asdf', new class {
    public function __toString() : string {
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

### SchemaLockedArray (extends XArray)

```php
// SchemaLockedArray will block the setting of any values if the key path is not allowlisted in a schema
$x = new SchemaLockedArray(new SchemaBuilder());

// throws SchemaLockedArrayUndefinedKeyException
$x->setVal('foo', 'bar');

// a schema can be built with a fluent API
$schemaBuilder = (new SchemaBuilder())
    ->with('foo')

    // also allowlists bar
    ->with('bar/baz')

    // also allowlists plugh and plugh/xyzzy
    ->with('plugh/xyzzy/fred');

// a schema can also be inferred from another XArray by analyzing the array's defined key paths
$x = new XArray([
    'foo' => 'qux',
    'bar' => [
        'baz' => true
    ],
    'plugh' => [
        'xyzzy' => [
            'fred' => [
                'sodium',
                'iodine'
            ]
        ]
    ]
]);
$schemaBuilder = SchemaBuilder::newFromXArray($x);

// either way, the SchemaLockedArray will only ever contain the key paths that are defined in the schema
$x = new SchemaLockedArray($schemaBuilder);
```

## Serialization

### JSON

```php
// An XArray (or any derived instance) can have a specialized serializer attached, such as JSON...
$x = (new XArray([
    'foo' => [
        'bar' => [
            "//baz",
            'qux'
        ]
    ],
    'plugh' => 'xyzzy'
]))->withSerializer(
    (new JsonSerializer())
        ->withUnescapedSlashes()
        ->withPrettyPrint()
);

// the serializer is engaged when writing the array into a textual representation
echo $x->toString();
echo strval($x);
```

```json
{
    "foo": {
        "bar": [
            "//baz",
            "qux"
        ]
    },
    "plugh": "xyzzy"
}
```

### XML

```php
// XML has the option to wrap the output in a root element to ensure valid XML schema
$x = (new XArray([
    'foo' => [
        'bar' => [
            'qux',
            'baz'
        ]
    ],
    'qux' => [
        'fred',
        'quxxx'
    ],
    'bar' => 'foo',
    'querty' => true,
    'asdf' => 'zxcv'
]))->withSerializer(
    (new XmlSerializer())
        ->withRootElement('xyzzy')
);
echo $x->toString();
```

```xml
<xyzzy>
  <foo>
    <bar>qux</bar>
    <bar>baz</bar>
  </foo>
  <qux>fred</qux>
  <qux>quxxx</qux>
  <bar>foo</bar>
  <querty>true</querty>
  <asdf>zxcv</asdf>
</xyzzy>
```
