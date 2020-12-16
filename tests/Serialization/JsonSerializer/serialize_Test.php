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
namespace modethirteen\XArray\Tests\Serialization\JsonSerializer;

use modethirteen\XArray\Serialization\JsonSerializer;
use modethirteen\XArray\Tests\Serialization\SerializerUnitTestCaseBase;

class serialize_Test extends SerializerUnitTestCaseBase  {

    /**
     * @return array
     */
    public static function class_withPrettyPrint_withUnescapedSlashes_expected_Provider() : array {
        $data = [];
        foreach([
            'without pretty print and without unescaped slashes' => [false, false, '{"foo":{"bar":["\/\/baz","qux"]},"plugh":"xyzzy"}'],
            'with pretty print and without unescaped slashes' => [true, false, <<<JSON
{
    "foo": {
        "bar": [
            "\/\/baz",
            "qux"
        ]
    },
    "plugh": "xyzzy"
}
JSON
            ],
            'without pretty print and with unescaped slashes' => [false, true, '{"foo":{"bar":["//baz","qux"]},"plugh":"xyzzy"}'],
            'with pretty print and with unescaped slashes' => [true, true, <<<JSON
{
    "foo": {
        "bar": [
            "//baz",
            "qux"
        ]
    },
    "plugh": "xyzzy"
}
JSON
            ]
        ] as $arguments) {
            foreach(self::class_Provider() as $classArguments) {
                $data[] = array_merge($classArguments, $arguments);
            }
        }
        return $data;
    }

    /**
     * @dataProvider class_withPrettyPrint_withUnescapedSlashes_expected_Provider
     * @param string $class
     * @param bool $withPrettyPrint
     * @param bool $withUnescapedSlashes
     * @param string $expected
     * @test
     */
    public function Can_output_serialized_array(string $class, bool $withPrettyPrint, bool $withUnescapedSlashes, string $expected) : void {

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
        $serializer = new JsonSerializer();
        if($withPrettyPrint) {
            $serializer = $serializer->withPrettyPrint();
        }
        if($withUnescapedSlashes) {
            $serializer = $serializer->withUnescapedSlashes();
        }

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals($expected, $result1);
        static::assertEquals($expected, $result2);
        static::assertEquals($result1, $result2);
    }
}
