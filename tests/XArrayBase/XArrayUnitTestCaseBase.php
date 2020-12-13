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

use modethirteen\XArray\Exception\SchemaLockedArrayUndefinedKeyException;
use modethirteen\XArray\SchemaBuilder;
use modethirteen\XArray\SchemaLockedArray;
use modethirteen\XArray\XArray;
use PHPUnit\Framework\TestCase;

abstract class XArrayUnitTestCaseBase extends TestCase  {

    /**
     * @var string
     */
    protected static string $class;

    /**
     * @param array $array
     * @param SchemaBuilder|null $schemaBuilder - default schema builder will be inferred from source array
     * @return XArray
     */
    protected function newXArray(array $array, SchemaBuilder $schemaBuilder = null) : XArray {
        $class = static::$class;
        if($class !== SchemaLockedArray::class) {
            return new $class($array);
        }

        // special schema bootstrapping for schema locked arrays
        $source = new XArray($array);
        $x = new SchemaLockedArray($schemaBuilder !== null ? $schemaBuilder : SchemaBuilder::newFromXArray($source));
        foreach($source->toFlattenedArray() as $key => $value) {
            try {
                $x->setVal($key, $value);
            } catch(SchemaLockedArrayUndefinedKeyException $e) {
                static::fail($e->getMessage());
            }
        }
        return $x;
    }
}