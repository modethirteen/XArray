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
namespace modethirteen\XArray\Tests\Serialization\Serializer;

use modethirteen\XArray\Serialization\Serializer;
use modethirteen\XArray\Tests\Serialization\SerializerUnitTestCaseBase;

class serialize_Test extends SerializerUnitTestCaseBase  {

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Can_output_serialized_array(string $class) : void {

        // arrange
        $x = self::newArrayFromClass($class, [
            'foo' => [
                'bar' => [
                    "//baz",
                    'qux'
                ]
            ],
            'plugh' => 'xyzzy'
        ]);
        $serializer = new Serializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('a:2:{s:3:"foo";a:1:{s:3:"bar";a:2:{i:0;s:5:"//baz";i:1;s:3:"qux";}}s:5:"plugh";s:5:"xyzzy";}', $result1);
        static::assertEquals('a:2:{s:3:"foo";a:1:{s:3:"bar";a:2:{i:0;s:5:"//baz";i:1;s:3:"qux";}}s:5:"plugh";s:5:"xyzzy";}', $result2);
        static::assertEquals($result1, $result2);
    }
}
