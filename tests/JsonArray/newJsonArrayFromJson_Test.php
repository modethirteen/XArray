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

class newJsonArrayFromJson_Test extends TestCase {

    /**
     * @test
     */
    public function Can_initialize_from_JSON() : void {

        // arrange
        $source = <<<JSON
{
  "shown": {
    "coffee": {
      "once": false,
      "previous": 1334548570,
      "lady": "stems",
      "planned": 216983651.27143478,
      "alike": "verb",
      "can": "creature"
    },
    "dance": false,
    "pick": true,
    "diagram": 1635807415.745319,
    "return": true,
    "worry": false
  },
  "piece": false,
  "noise": "book",
  "pipe": 654136905.9745188,
  "unknown": "led",
  "moment": false
}
JSON;

        // act
        $x = JsonArray::newJsonArrayFromJson($source);

        // assert
        $this->assertEquals([
            'shown' => [
                'coffee' => [
                    'once' => false,
                    'previous' => 1334548570,
                    'lady' => 'stems',
                    'planned' => 216983651.27143478,
                    'alike' => 'verb',
                    'can' => 'creature'
                ],
                'dance' => false,
                'pick' => true,
                'diagram' => 1635807415.745319,
                'return' => true,
                'worry' => false
            ],
            'piece' => false,
            'noise' => 'book',
            'pipe' => 654136905.9745188,
            'unknown' => 'led',
            'moment' => false
        ], $x->toArray());
    }
}