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
namespace modethirteen\XArray;

use modethirteen\XArray\Exception\SchemaLockedArrayUndefinedKeyException;

class SchemaLockedArray extends XArray {

    /**
     * @var XArray
     */
    private XArray $schema;

    /**
     * @param SchemaBuilder $builder
     */
    public function __construct(SchemaBuilder $builder) {
        $this->schema = $builder->getSchema();
        parent::__construct([]);
    }

    /**
     * Set or replace a key value
     *
     * @param string $key
     * @param mixed $value
     * @throws SchemaLockedArrayUndefinedKeyException
     */
    public function setVal(string $key, $value = null) : void {
        if(!in_array($key, $this->schema->getKeys())) {
            throw new SchemaLockedArrayUndefinedKeyException($key);
        }
        parent::setVal($key, $value);
    }
}
