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
namespace modethirteen\XArray\tests\XArrayBase;

abstract class toArray_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     */
    public function Can_return_new_array_created_from_empty_ctor() {
    
        // arrange
        $X = $this->newXArray();
        $X->setVal('foo/bar', 'baz');
        
        // act
        $result = $X->toArray();
        
        // assert
        $this->assertEquals(['foo' => ['bar' => 'baz']], $result);
    }
    
    /**
     * @test
     */
    public function Can_return_non_modified_array_created_from_array() {
    
        // arrange
        $array = ['foo' => ['bar' => 'baz']];
        $X = $this->newXArray($array);
        
        // act
        $result = $X->toArray();
        
        // assert
        $this->assertEquals(['foo' => ['bar' => 'baz']], $result);
    }

    /**
     * @test
     */
    public function Can_return_modified_array_created_from_array() {
        
        // arrange
        $array = ['foo' => ['bar' => 'baz']];
        $X = $this->newXArray($array);
        $X->setVal('qux', 'fred');
        
        // act
        $result = $X->toArray();
        
        // assert
        $this->assertEquals(['foo' => ['bar' => 'baz'], 'qux' => 'fred'], $result);
    }
}