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

use modethirteen\XArray\SchemaLockedArray;

abstract class toMergedArray_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     */
    public function Array_elements_in_leaf_nodes_are_combined_with_no_overwriting_1() : void {

        // arrange
        $first = self::newArray([
            'foo' => 'abc',
            'bar' => [
                'a', 'b', 'c'
            ]
        ]);
        $second = self::newArray([
            'bar' => [
                'd', 'e', 'f'
            ]
        ]);

        // act
        $merged = $first->toMergedArray($second);

        // assert
        $expected = [
            'foo' => 'abc',
            'bar' => [
                'a', 'b', 'c', 'd', 'e', 'f'
            ]
        ];
        static::assertEquals($expected, $merged);
    }

    /**
     * @test
     */
    public function Array_elements_in_leaf_nodes_are_combined_with_no_overwriting_2() : void {
        if(static::$class === SchemaLockedArray::class) {

            // schema locked array values must have a string index
            static::addToAssertionCount(1);
            return;
        }

        // arrange
        $first = self::newArray([
            'foo', 'bar'
        ]);
        $second = self::newArray([
            'baz', 'qux', 'fred'
        ]);

        // act
        $merged = $first->toMergedArray($second);

        // assert
        $expected = ['foo', 'bar', 'baz', 'qux', 'fred'];
        static::assertEquals($expected, $merged);
    }

    /**
     * @test
     */
    public function Strings_with_named_indexed_are_overwritten() : void {

        // arrange
        $first = self::newArray([
            'foo' => 'abc',
            'bar' => 'efg',
            [
                'baz' => 'hij'
            ]
        ]);
        $second = self::newArray([
            'foo' => '123',
            [
                'baz' => '321'
            ]
        ]);

        // act
        $merged = $first->toMergedArray($second);

        // assert
        if(static::$class === SchemaLockedArray::class) {

            // schema locked array values must have a string index
            static::assertEquals([
                'foo' => '123',
                'bar' => 'efg'
            ], $merged);
        } else {
            static::assertEquals([
                'foo' => '123',
                'bar' => 'efg',
                [
                    'baz' => '321'
                ]
            ], $merged);
        }
    }
}
