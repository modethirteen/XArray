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

abstract class getVal_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     * @dataProvider source_xpath_expected_Provider
     * @param array $source
     * @param string $xpath
     * @param mixed $expected
     */
    public function Can_get_value(array $source, string $xpath, $expected) : void {

        // arrange
        $x = $this->newXArray($source);

        // act
        $result = $x->getVal($xpath);

        // assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function Can_get_default() : void {

        // arrange
        $x = $this->newXArray(['foo' => 'bar']);

        // act
        $result = $x->getVal('qux', true);

        // assert
        $this->assertEquals(true, $result);
    }

    /**
     * @test
     */
    public function Empty_key_returns_null() : void {

        // arrange
        $x = $this->newXArray(['foo' => 'bar']);

        // act
        $result = $x->getVal('');

        // assert
        $this->assertNull($result);
    }
    
    /**
     * @return array
     */
    public static function source_xpath_expected_Provider() : array {
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
            ]
        ];
    }
}