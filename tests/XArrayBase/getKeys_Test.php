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
namespace modethirteen\XArray\Tests\XArrayBase;

use modethirteen\XArray\SchemaBuilder;
use modethirteen\XArray\XArray;

abstract class getKeys_Test extends XArrayUnitTestCaseBase {

    /**
     * @return array
     */
    public static function source_key_expected_Provider() : array {
        return [
            'empty level one' => [
                [],
                'foo',
                ['foo']
            ],
            'empty level two' => [
                ['foo' => ''],
                'foo/bar',
                ['foo', 'foo/bar']
            ],
            'empty level three' => [
                ['foo' => ['bar' => '']],
                'foo/bar/baz',
                ['foo', 'foo/bar', 'foo/bar/baz']
            ],
            'replace string level one' => [
                ['qux' => 'fred', 'foo' => 'bar'],
                'foo',
                ['qux', 'foo']
            ],
            'replace string level two' => [
                ['qux' => 'fred', 'foo' => ['bar' => 'baz']],
                'foo/bar',
                ['qux', 'foo', 'foo/bar']
            ],
            'replace string level three' => [
                ['qux' => 'fred', 'foo' => ['bar' => ['baz' => 'qux']]],
                'foo/bar/baz',
                ['qux', 'foo', 'foo/bar', 'foo/bar/baz']
            ],
            'replace array level one' => [
                ['qux' => 'fred', 'foo' => ['bar', 'baz']],
                'foo',
                ['qux', 'foo']
            ],
            'replace array level two' => [
                ['qux' => 'fred', 'foo' => ['bar' => ['baz', 'qux']]],
                'foo/bar',
                ['qux', 'foo', 'foo/bar']
            ],
            'replace array level three' => [
                ['qux' => 'fred', 'foo' => ['bar' => ['baz' => ['qux', 'fred']]]],
                'foo/bar/baz',
                ['qux', 'foo', 'foo/bar', 'foo/bar/baz']
            ],
            'new string level one' => [
                ['qux' => 'fred', 'foo' => 'bar'],
                'plugh',
                ['qux', 'foo', 'plugh']
            ],
            'new string level two' => [
                ['qux' => 'fred', 'foo' => ['bar' => 'baz']],
                'plugh/xyzzy',
                ['qux', 'foo', 'foo/bar', 'plugh', 'plugh/xyzzy']
            ],
            'new string level three' => [
                ['qux' => 'fred', 'foo' => ['bar' => ['baz' => 'qux']]],
                'plugh/xyzzy/fred',
                ['qux', 'foo', 'foo/bar', 'foo/bar/baz', 'plugh', 'plugh/xyzzy', 'plugh/xyzzy/fred']
            ],
            'branching keys' => [
                ['qux' => 'fred', 'foo' => ['bar' => ['baz' => ['qux', 'fred']]]],
                'foo/plugh/xyzzy',
                ['qux', 'foo', 'foo/bar', 'foo/bar/baz', 'foo/plugh', 'foo/plugh/xyzzy']
            ]
        ];
    }

    /**
     * @test
     * @dataProvider source_key_expected_Provider
     * @param array $source
     * @param string $key
     * @param array $expected
     */
    public function Can_get_keys(array $source, string $key, array $expected): void {

        // arrange
        $x = $this->newXArray($source, SchemaBuilder::newFromXArray(new XArray($source))->with($key));

        // act
        $x->setVal($key, 'abcdf');
        $result = $x->getKeys();

        // assert
        $this->assertEquals($expected, $result);
    }
}