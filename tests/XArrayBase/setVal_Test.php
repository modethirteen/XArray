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

abstract class setVal_Test extends XArrayUnitTestCaseBase {

    /**
     * @return array
     */
    public static function source_key_value_expected_Provider() : array {
        return [
            'empty with string level one' => [
                [],
                'foo',
                'bar',
                ['foo' => 'bar']
            ],
            'empty with string level two' => [
                [],
                'foo/bar',
                'baz',
                ['foo' => ['bar' => 'baz']]
            ],
            'empty with string level three' => [
                [],
                'foo/bar/baz',
                'qux',
                ['foo' => ['bar' => ['baz' => 'qux']]]
            ],
            'empty with array level one' => [
                [],
                'foo',
                ['bar', 'baz'],
                ['foo' => ['bar', 'baz']]
            ],
            'empty with array level two' => [
                [],
                'foo/bar',
                ['baz', 'qux'],
                ['foo' => ['bar' => ['baz', 'qux']]]
            ],
            'empty with array level three' => [
                [],
                'foo/bar/baz',
                ['qux', 'fred'],
                ['foo' => ['bar' => ['baz' => ['qux', 'fred']]]]
            ],
            'empty with bool level one' => [
                [],
                'foo',
                true,
                ['foo' => true]
            ],
            'empty with bool level two' => [
                [],
                'foo/bar',
                true,
                ['foo' => ['bar' => true]]
            ],
            'empty with bool level three' => [
                [],
                'foo/bar/baz',
                true,
                ['foo' => ['bar' => ['baz' => true]]]
            ],
            'empty with int level one' => [
                [],
                'foo',
                123,
                ['foo' => 123]
            ],
            'empty with int level two' => [
                [],
                'foo/bar',
                123,
                ['foo' => ['bar' => 123]]
            ],
            'empty with int level three' => [
                [],
                'foo/bar/baz',
                123,
                ['foo' => ['bar' => ['baz' => 123]]]
            ],
            'string with string level one' => [
                ['foo' => 'bar'],
                'foo',
                'qux',
                ['foo' => 'qux']
            ],
            'string with string level two' => [
                ['foo' => ['bar' => 'baz']],
                'foo/bar',
                'qux',
                ['foo' => ['bar' => 'qux']]
            ],
            'string with string level three' => [
                ['foo' => ['bar' => ['baz' => 'fred']]],
                'foo/bar/baz',
                'qux',
                ['foo' => ['bar' => ['baz' => 'qux']]]
            ],
            'string with null level one' => [
                ['foo' => 'bar'],
                'foo',
                null,
                []
            ],
            'string with null level two' => [
                ['foo' => ['bar' => 'baz']],
                'foo/bar',
                null,
                ['foo' => []]
            ],
            'string with null level three' => [
                ['foo' => ['bar' => ['baz' => 'fred']]],
                'foo/bar/baz',
                null,
                ['foo' => ['bar' => []]]
            ],
            'string with array level one' => [
                ['foo' => 'bar'],
                'foo',
                ['baz', 'qux'],
                ['foo' => ['baz', 'qux']]
            ],
            'string with array level two' => [
                ['foo' => ['bar' => 'baz']],
                'foo/bar',
                ['fred', 'qux'],
                ['foo' => ['bar' => ['fred', 'qux']]]
            ],
            'string with array level three' => [
                ['foo' => ['bar' => ['baz' => 'fred']]],
                'foo/bar/baz',
                ['quxx', 'qux'],
                ['foo' => ['bar' => ['baz' => ['quxx', 'qux']]]]
            ],
            'array with string level one' => [
                ['foo' => ['baz', 'qux']],
                'foo',
                'bar',
                ['foo' => 'bar']
            ],
            'array with string level two' => [
                ['foo' => ['bar' => ['baz', 'qux']]],
                'foo/bar',
                'fred',
                ['foo' => ['bar' => 'fred']]
            ],
            'array with string level three' => [
                ['foo' => ['bar' => ['baz' => ['fred', 'qux']]]],
                'foo/bar/baz',
                'quxx',
                ['foo' => ['bar' => ['baz' => 'quxx']]]
            ],
            'array with null level one' => [
                ['foo' => ['baz', 'qux']],
                'foo',
                null,
                []
            ],
            'array with null level two' => [
                ['foo' => ['bar' => ['baz', 'qux']]],
                'foo/bar',
                null,
                ['foo' => []]
            ],
            'array with null level three' => [
                ['foo' => ['bar' => ['baz' => ['fred', 'qux']]]],
                'foo/bar/baz',
                null,
                ['foo' => ['bar' => []]]
            ],
            'array with array level one' => [
                ['foo' => ['fred', 'quxx']],
                'foo',
                ['baz', 'qux'],
                ['foo' => ['baz', 'qux']]
            ],
            'array with array level two' => [
                ['foo' => ['bar' => ['fred', 'quxx']]],
                'foo/bar',
                ['baz', 'qux'],
                ['foo' => ['bar' => ['baz', 'qux']]]
            ],
            'array with array level three' => [
                ['foo' => ['bar' => ['baz' => ['fred', 'quxx']]]],
                'foo/bar/baz',
                ['apple', 'pear'],
                ['foo' => ['bar' => ['baz' => ['apple', 'pear']]]]
            ],
            'replace leaf bool with key' => [
                ['foo' => true],
                'foo/bar',
                'qux',
                ['foo' => ['bar' => 'qux']]
            ],
            'replace leaf string with key' => [
                ['foo' => 'bar'],
                'foo/bar',
                'qux',
                ['foo' => ['bar' => 'qux']]
            ],
            'replace leaf int with key' => [
                ['foo' => 'bar'],
                'foo/bar',
                'qux',
                ['foo' => ['bar' => 'qux']]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider source_key_value_expected_Provider
     * @param array $source
     * @param string $key
     * @param mixed $value
     * @param array $expected
     */
    public function Can_set_value(array $source, string $key, $value, array $expected) : void {

        // arrange
        $x = $this->newXArray($source);

        // act
        $x->setVal($key, $value);

        // assert
        $this->assertEquals($expected, $x->toArray());
    }
}