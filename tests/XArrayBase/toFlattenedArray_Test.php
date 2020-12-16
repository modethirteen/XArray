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

use modethirteen\XArray\SchemaBuilder;

abstract class toFlattenedArray_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     */
    public function Can_return_new_array_created_from_empty_ctor() : void {
    
        // arrange
        $x = self::newArray([], (new SchemaBuilder())->with('foo/bar'));
        $x->setVal('foo/bar', 'baz');
        
        // act
        $result = $x->toFlattenedArray();
        
        // assert
        $this->assertEquals(['foo/bar' => 'baz'], $result);
    }
    
    /**
     * @test
     */
    public function Can_return_non_modified_array_created_from_array() : void {
    
        // arrange
        $array = ['foo' => ['bar' => 'baz']];
        $x = self::newArray($array);
        
        // act
        $result = $x->toFlattenedArray();
        
        // assert
        $this->assertEquals(['foo/bar' => 'baz'], $result);
    }

    /**
     * @test
     */
    public function Can_return_modified_array_created_from_array() : void {
        
        // arrange
        $array = [
            'foo' => ['bar' => 'baz'],
            541 => ['attr' => '#text']
        ];
        $x = self::newArray($array, (new SchemaBuilder())
            ->with('foo/bar')
            ->with('qux/fred')
            ->with('a/b/c/d/e')
            ->with('a/b/f/g')
            ->with('qux/plugh')
            ->with('bazz/foo/foobar/fred')
        );
        $x->setVal('qux', 'fred');
        $x->setVal('a/b/c/d/e', true);
        $x->setVal('a/b/f/g', false);
        $x->setVal('qux/plugh', 'xyzzy');
        $x->setVal('bazz/foo/foobar/fred', ['sodium', 'argon', 'iodine']);
        
        // act
        $result = $x->toFlattenedArray();
        
        // assert
        $this->assertEquals([
            'foo/bar' => 'baz',
            'qux/plugh' => 'xyzzy',
            'a/b/c/d/e' => true,
            'a/b/f/g' => false,
            'bazz/foo/foobar/fred' => ['sodium', 'argon', 'iodine']
        ], $result);
    }
}
