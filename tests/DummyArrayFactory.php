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
use modethirteen\XArray\IArray;
use modethirteen\XArray\SchemaBuilder;
use modethirteen\XArray\SchemaLockedArray;
use modethirteen\XArray\XArray;

class DummyArrayFactory {

    /**
     * @var string
     */
    private string $class;

    /**
     * @var SchemaBuilder|null
     */
    private ?SchemaBuilder $schemaBuilder = null;

    /**
     * @param string $class
     */
    public function __construct(string $class) {
        $this->class = $class;
    }

    /**
     * @param array $array
     * @return IArray
     * @throws SchemaLockedArrayUndefinedKeyException
     */
    public function newArray(array $array) : IArray {
        $class = $this->class;
        if($this->class !== SchemaLockedArray::class) {
            return new $class($array);
        }
        // special schema bootstrapping for schema locked arrays
        $source = new XArray($array);
        $x = new SchemaLockedArray(
            $this->schemaBuilder !== null
                ? $this->schemaBuilder
                : SchemaBuilder::newFromXArray($source)
        );
        foreach($source->toFlattenedArray() as $key => $value) {
            $x->setVal($key, $value);
        }
        return $x;
    }

    /**
     * @param SchemaBuilder $schemaBuilder
     * @return static
     */
    public function withSchemaBuilder(SchemaBuilder $schemaBuilder) : object {
        $instance = clone $this;
        $instance->schemaBuilder = $schemaBuilder;
        return $instance;
    }
}
