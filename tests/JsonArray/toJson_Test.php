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
namespace modethirteen\XArray\Tests\JsonArray;

use modethirteen\XArray\JsonArray;
use PHPUnit\Framework\TestCase;

class toJson_Test extends TestCase  {

    /**
     * @return array
     */
    public static function withPrettyPrint_withUnescapedSlashes_expected_Provider() : array {
        return [
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
        ];
    }

    /**
     * @dataProvider withPrettyPrint_withUnescapedSlashes_expected_Provider
     * @param bool $withPrettyPrint
     * @param bool $withUnescapedSlashes
     * @param string $expected
     * @test
     */
    public function Can_output_JSON(bool $withPrettyPrint, bool $withUnescapedSlashes, string $expected) : void {

        // arrange
        $x = new JsonArray([
            'foo' => [
                'bar' => [
                    "//baz",
                    'qux'
                ]
            ],
            'plugh' => 'xyzzy'
        ]);
        if($withPrettyPrint) {
            $x = $x->withPrettyPrint();
        }
        if($withUnescapedSlashes) {
            $x = $x->withUnescapedSlashes();
        }

        // act
        $result = $x->toJson();

        // assert
        static::assertEquals($expected, $result);
    }
}
