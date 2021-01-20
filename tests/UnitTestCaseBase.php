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
namespace modethirteen\XArray\Tests;

use modethirteen\XArray\Exception\SchemaLockedArrayUndefinedKeyException;
use modethirteen\XArray\ArrayInterface;
use modethirteen\XArray\SchemaBuilder;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCaseBase extends TestCase  {

    /**
     * @param string $class - XArray concrete class (XArray, MutableXArray, ...)
     * @param array $array - source array data
     * @param SchemaBuilder|null $schemaBuilder - default schema builder will be inferred from source array
     * @return ArrayInterface
     */
    final protected static function newArrayFromClass(string $class, array $array, SchemaBuilder $schemaBuilder = null) : ArrayInterface {
        $factory = new DummyArrayFactory($class);
        if($schemaBuilder !== null) {
            $factory = $factory->withSchemaBuilder($schemaBuilder);
        }
        try {
            return $factory->newArray($array);
        } catch(SchemaLockedArrayUndefinedKeyException $e) {
            static::fail($e->getMessage());
        }
    }
}
