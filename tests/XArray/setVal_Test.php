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
namespace modethirteen\XArray\Tests\XArray;

use modethirteen\XArray\XArray;

class setVal_Test extends \modethirteen\XArray\Tests\XArrayBase\setVal_Test  {

    /**
     * @var string
     */
    protected static string $class = XArray::class;

    /**
     * @test
     */
    public function Cannot_mutate_original_array() : void {
        
        // arrange
        $array = ['foo' => ['bar' => 'baz']];
        $X = new XArray($array);
        
        // act
        $X->setVal('qux', 'fred');
        
        // assert
        $this->assertEquals(['foo' => ['bar' => 'baz']], $array);
    }
}