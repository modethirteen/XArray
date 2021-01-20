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

class MutableXArray extends XArray{

    /**
     * @param array $array - reference to array
     */
    public function __construct(array &$array) {
        parent::__construct($array);
        $this->array = &$array;
    }

    /**
     * Retrieve all possible key paths in the array
     *
     * @return string[]
     */
    public function getKeys() : array {

        // mutable xarray ignores the cached key path list, as the array may be mutated outside of this instance
        return $this->getKeysHelper('', $this->array);
    }
}
