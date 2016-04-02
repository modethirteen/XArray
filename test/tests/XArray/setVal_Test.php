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
namespace MindTouch\XArray\test\tests\XArray;

use MindTouch\XArray\XArray;

class setVal_Test extends \MindTouch\XArray\test\tests\XArrayBase\setVal_Test  {

    /**
     * @var string
     */
    protected static $class = 'MindTouch\XArray\XArray';

    /**
     * @test
     */
    public function Cannot_mutate_original_array() {
        
        // arrange
        $array = ['foo' => ['bar' => 'baz']];
        $X = new XArray($array);
        
        // act
        $X->setVal('qux', 'fred');
        
        // assert
        $this->assertEquals(['foo' => ['bar' => 'baz']], $array);
    }
}