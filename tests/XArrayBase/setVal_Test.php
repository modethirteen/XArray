<?php
/**
 * MindTouch XArray
 *
 * Copyright (C) 2006-2016 MindTouch, Inc.
 * www.mindtouch.com  oss@mindtouch.com
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
namespace MindTouch\XArray\tests\XArrayBase;

abstract class setVal_Test extends XArrayUnitTestCaseBase {

    /**
     * @test
     * @dataProvider dataProvider
     * @param array $source
     * @param string $xpath
     * @param string|array $value
     * @param array $expected
     */
    public function Can_set_value(array $source, $xpath, $value, $expected) {

        // arrange
        $Array = $this->newXArray($source);

        // act
        $Array->setVal($xpath, $value);

        // assert
        $this->assertEquals($expected, $Array->toArray());
    }

    /**
     * @return array
     */
    public static function dataProvider() {
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
            ]
        ];
    }
}