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

abstract class getString_Test extends XArrayUnitTestCaseBase  {

    /**
     * @return array
     */
    public static function source_key_expected_Provider() : array {
        return [
            'empty level one' => [
                [],
                'foo',
                '',
            ],
            'empty level two' => [
                ['foo' => ''],
                'foo/bar',
                '',
            ],
            'empty level three' => [
                ['foo' => ['bar' => '']],
                'foo/bar/baz',
                '',
            ],
            'string level one' => [
                ['foo' => 'bar'],
                'foo',
                'bar',
            ],
            'string level two' => [
                ['foo' => ['bar' => 'baz']],
                'foo/bar',
                'baz',
            ],
            'string level three' => [
                ['foo' => ['bar' => ['baz' => 'qux']]],
                'foo/bar/baz',
                'qux',
            ],

            // XArray::getVal(...) only gets first element of array value
            'array level one' => [
                ['foo' => ['bar', 'baz']],
                'foo',
                'bar',
            ],
            'array level two' => [
                ['foo' => ['bar' => ['baz', 'qux']]],
                'foo/bar',
                'baz'
            ],
            'array level three' => [
                ['foo' => ['bar' => ['baz' => ['qux', 'fred']]]],
                'foo/bar/baz',
                'qux'
            ],
            'array of arrays level zero' => [
                [['foo', 'bar'], ['baz'], ['qux']],
                '',
                ''
            ],
            'array of arrays level one' => [
                ['plugh' => [['foo', 'bar'], ['baz'], ['qux']]],
                'plugh',
                '["foo","bar"]'
            ],
            'array of arrays level two' => [
                ['plugh' => ['xyzzy' => [['foo', 'bar'], ['baz'], ['qux']]]],
                'plugh/xyzzy',
                '["foo","bar"]'
            ],
            'array of arrays level three' => [
                ['plugh' => ['xyzzy' => ['ogre' => [['foo', 'bar'], ['baz'], ['qux']]]]],
                'plugh/xyzzy/ogre',
                '["foo","bar"]'
            ],
            'array of arrays level four' => [
                ['plugh' => ['xyzzy' => ['ogre' => ['nivek' => [['foo', 'bar'], ['baz'], ['qux']]]]]],
                'plugh/xyzzy/ogre/nivek',
                '["foo","bar"]'
            ],
            'array of arrays level two with level one key' => [
                ['plugh' => ['xyzzy' => [['foo', 'bar'], ['baz'], ['qux']]]],
                'plugh',
                '{"xyzzy":[["foo","bar"],["baz"],["qux"]]}'
            ],
            'array of arrays level three with level two key' => [
                ['plugh' => ['xyzzy' => ['ogre' => [['foo', 'bar'], ['baz'], ['qux']]]]],
                'plugh/xyzzy',
                '{"ogre":[["foo","bar"],["baz"],["qux"]]}'
            ],
            'array of arrays level four with level three key' => [
                ['plugh' => ['xyzzy' => ['ogre' => ['nivek' => [['foo', 'bar'], ['baz'], ['qux']]]]]],
                'plugh/xyzzy/ogre',
                '{"nivek":[["foo","bar"],["baz"],["qux"]]}'
            ],
            'object with __toString' => [
                ['foo' => new class {
                    public function __toString() : string {
                        return 'asdf';
                    }
                }],
                'foo',
                'asdf'
            ],
            'bool true' => [
                ['foo' => true],
                'foo',
                'true'
            ],
            'bool false' => [
                ['foo' => false],
                'foo',
                'false'
            ],
            'int' => [
                ['foo' => 123],
                'foo',
                '123'
            ],
            'float' => [
                ['foo' => 1.23],
                'foo',
                '1.23'
            ],
            'function' => [
                ['foo' => function() {
                    return 'qux';
                }],
                'foo',
                'qux'
            ],
            'null' => [
                ['foo' => null],
                'foo',
                ''
            ]
        ];
    }

    /**
     * @test
     * @dataProvider source_key_expected_Provider
     * @param array $source
     * @param string $key
     * @param string $expected
     */
    public function Can_get_string_value(array $source, string $key, string $expected) : void {

        // arrange
        $x = self::newArray($source);

        // act
        $result = $x->getString($key);

        // assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function Can_get_default() : void {

        // arrange
        $x = self::newArray(['foo' => 'bar']);

        // act
        $result = $x->getString('qux', 'fred');

        // assert
        $this->assertEquals('fred', $result);
    }

    /**
     * @test
     */
    public function Empty_key_returns_empty_string() : void {

        // arrange
        $x = self::newArray(['foo' => 'bar']);

        // act
        $result = $x->getString('');

        // assert
        $this->assertEquals('', $result);
    }
}