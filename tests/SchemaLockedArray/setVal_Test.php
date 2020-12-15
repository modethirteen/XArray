<?php declare(strict_types=1);
/**
 * XArray
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace modethirteen\XArray\Tests\SchemaLockedArray;

use modethirteen\XArray\Exception\SchemaLockedArrayUndefinedKeyException;
use modethirteen\XArray\SchemaBuilder;
use modethirteen\XArray\SchemaLockedArray;
use modethirteen\XArray\Tests\XArrayBase\XArrayUnitTestCaseBase;

class setVal_Test extends XArrayUnitTestCaseBase {

    /**
     * @return array
     */
    public static function key_value_expected_Provider() : array {
        return [
            'string level one' => [
                'foo',
                'bar',
                ['foo' => 'bar']
            ],
            'string level two' => [
                'foo/bar',
                'baz',
                ['foo' => ['bar' => 'baz']]
            ],
            'string level three' => [
                'foo/bar/baz',
                'qux',
                ['foo' => ['bar' => ['baz' => 'qux']]]
            ],
            'array level one' => [
                'foo',
                ['bar', 'baz'],
                ['foo' => ['bar', 'baz']]
            ],
            'array level two' => [
                'foo/bar',
                ['baz', 'qux'],
                ['foo' => ['bar' => ['baz', 'qux']]]
            ],
            'array level three' => [
                'foo/bar/baz',
                ['qux', 'fred'],
                ['foo' => ['bar' => ['baz' => ['qux', 'fred']]]]
            ],
            'null level one' => [
                'foo',
                null,
                []
            ],
            'null level two' => [
                'foo/bar',
                null,
                ['foo' => []]
            ],
            'null level three' => [
                'foo/bar/baz',
                null,
                ['foo' => ['bar' => []]]
            ],
            'bool level one' => [
                'foo',
                true,
                ['foo' => true]
            ],
            'bool level two' => [
                'foo/bar',
                true,
                ['foo' => ['bar' => true]]
            ],
            'bool level three' => [
                'foo/bar/baz',
                true,
                ['foo' => ['bar' => ['baz' => true]]]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider key_value_expected_Provider
     * @param string $key
     * @param mixed $value
     * @param array $expected
     * @throws SchemaLockedArrayUndefinedKeyException
     */
    public function Can_set_value_for_allowed_keys(string $key, $value, array $expected) : void {

        // arrange
        $schema = (new SchemaBuilder())
            ->with($key);
        $x = new SchemaLockedArray($schema);

        // act
        $x->setVal($key, $value);

        // assert
        $this->assertEquals($expected, $x->toArray());
    }

    /**
     * @test
     */
    public function Cannot_set_value_for_key() : void {

        // assert
        static::expectException(SchemaLockedArrayUndefinedKeyException::class);

        // arrange
        $schema = (new SchemaBuilder())
            ->with('foo');
        $x = new SchemaLockedArray($schema);

        // act
        $x->setVal('bar', 'baz');
    }
}