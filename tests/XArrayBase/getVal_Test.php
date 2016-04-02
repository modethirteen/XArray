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

abstract class getVal_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     * @dataProvider dataProvider
     * @param array $source
     * @param string $xpath
     * @param array $expected
     */
    public function Can_get_value(array $source, $xpath, $expected) {

        // arrange
        $Array = $this->newXArray($source);

        // act
        $result = $Array->getVal($xpath);

        // assert
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @return array
     */
    public static function dataProvider() {
        return [
            'empty level zero' => [
                [],
                '',
                []
            ],
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
            'string level zero' => [
                ['foo' => 'bar'],
                '',
                ['foo' => 'bar']
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
            'array level zero' => [
                ['foo' => ['bar', 'baz']],
                '',
                ['foo' => ['bar', 'baz']],
            ],
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
            ]
        ];
    }
}