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

use modethirteen\TypeEx\StringEx;
use modethirteen\XArray\Serialization\SerializerInterface;
use modethirteen\XArray\Serialization\Serializer;

/**
 * Class XArray - get/set accessors for arrays
 *
 * @package modethirteen\XArray
 */
class XArray implements ArrayInterface {

    /**
     * @param array $array
     * @param string $key - the array path to return, i.e. /pages/content
     * @param bool $isArrayReturnAllowed - can return array or first element of array
     * @param mixed $default - if the key is not found, this value will be returned
     * @return mixed|null
     * @noinspection PhpMissingReturnTypeInspection
     */
    private static function getValHelper(array $array, string $key, bool $isArrayReturnAllowed, $default) {
        if($key === '') {
            return $default;
        }
        $keys = explode('/', $key);
        $count = count($keys);
        $i = 0;
        foreach($keys as $k => $val) {
            $i++;
            if($val === '') {
                continue;
            }
            if(isset($array[$val]) && !is_array($array[$val])) {
                if($array[$val] !== null && $i === $count) {
                    return $array[$val];
                }
                return $default;
            }
            if(isset($array[$val])) {
                $array = $array[$val];
            } else {
                return $default;
            }
            if(!$isArrayReturnAllowed && is_array($array) && key($array) === 0) {
                $array = current($array);
            }
        }
        return $array;
    }

    /**
     * Array merge that combines array leaf nodes without overwriting
     *
     * @param array $first
     * @param array $second
     * @return array
     */
    private static function merge(array $first, array $second) : array {
        $merged = $first;
        foreach($second as $k => $v) {
            if(is_array($v) && isset($merged[$k]) && is_array($merged[$k])) {
                $merged[$k] = self::merge($merged[$k], $v);
            } else if(is_int($k)) {
                $merged[] = $v;
            } else {
                $merged[$k] = $v;
            }
        }
        return $merged;
    }

    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private static function setValHelper(array &$array, string $key, $value) : void {
        $keys = explode('/', $key);
        $count = count($keys);
        $i = 0;
        foreach($keys as $key) {
            $i++;
            if($i === $count) {
                if($value === null) {
                    unset($array[$key]);
                    return;
                }
                $array[$key] = $value;
            } else if(!isset($array[$key])) {
                $array[$key] = [];
            }
            if(!is_array($array[$key]) ) {
                if($i === $count) {

                    // we're at a leaf path segment with no intention to replace the segment with a collection
                    return;
                }
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
    }

    /**
     * @var array
     */
    protected array $array = [];

    /**
     * Lazy initialized list of array keys is reset whenever array is mutated
     *
     * @var array|null
     */
    private ?array $keys = null;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param array|null $array $array - array values to create XArray from. If not supplied, XArray will start empty
     */
    public function __construct(array $array = null) {
        $this->array = $array !== null ? $array : [];
        $this->serializer = new Serializer();
    }

    public function __toString() : string {
        return $this->toString();
    }

    public function getKeys() : array {
        if($this->keys === null) {
            $this->keys = $this->getKeysHelper('', $this->array);
        }
        return $this->keys;
    }

    /** @noinspection PhpMissingReturnTypeInspection */
    public function getVal(string $key, $default = null) {
        return self::getValHelper($this->array, $key, true, $default);
    }

    public function getString(string $key, string $default = '') : string {
        return StringEx::stringify(self::getValHelper($this->array, $key, false, $default));
    }

    public function setVal(string $key, $value = null) : void {
        $this->setValHelper($this->array, $key, $value);

        // array has been mutated, reset key path cache
        $this->keys = null;
    }

    public function toArray() : array {
        return $this->array;
    }

    public function toFlattenedArray() : array {
        $values = [];
        foreach($this->getKeys() as $key) {
            $value = $this->getVal($key);
            if(is_array($value) && count(array_filter(array_keys($value), 'is_string')) > 0) {

                // a collection that has string indices: it is safe assumption that it is key path segment
                continue;
            }
            $values[$key] = $value;
        }
        return $values;
    }

    public function toMergedArray(ArrayInterface $array) : array {
        return self::merge($this->toArray(), $array->toArray());
    }

    public function toString() : string {
        return $this->serializer->serialize($this);
    }

    /**
     * @param SerializerInterface $serializer
     * @return static
     */
    public function withSerializer(SerializerInterface $serializer) : object {
        $instance = clone $this;
        $instance->serializer = $serializer;
        return $instance;
    }

    /**
     * Internal helper to walk array recursively, building a list of xarray keys
     *
     * @param string $key
     * @param array $array
     * @return string[]
     */
    protected function getKeysHelper(string $key, array $array) : array {
        $keys = [];
        foreach($array as $k => $val) {
            if(!is_string($k)) {

                // keys are array path segments, it's safe to assume non-string keys are indexes for simple collections
                continue;
            }
            if($key !== null && $key !== '') {
                $k = "{$key}/{$k}";
            }
            $keys[] = $k;
            if(is_array($val)) {
                $keys = array_merge($keys, $this->getKeysHelper($k, $val));
            }
        }
        return $keys;
    }
}