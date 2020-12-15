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

use Closure;

/**
 * Class XArray - get/set accessors for arrays
 *
 * @package modethirteen\XArray
 */
class XArray {

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
     * Get string representation of value
     *
     * @param mixed $value
     * @return string
     */
    private static function getStringValue($value) : string {
        if($value === null) {
            return '';
        }
        if(is_string($value)) {
            return $value;
        }
        if(is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if(is_array($value)) {
            return implode(',', array_map(function($v) {
                return self::getStringValue($v);
            }, $value));
        }
        if($value instanceof Closure) {
            return strval($value());
        }
        return strval($value);
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
     * @param array|null $array $array - array values to create XArray from. If not supplied, XArray will start empty
     */
    public function __construct(array $array = null) {
        $this->array = $array !== null ? $array : [];
    }

    /**
     * Retrieve all possible key paths in the array
     *
     * @return string[]
     */
    public function getKeys() : array {
        if($this->keys === null) {
            $this->keys = $this->getKeysHelper('', $this->array);
        }
        return $this->keys;
    }

    /**
     * Find $key in the XArray, which is delimited by /
     *
     * If the found value is itself an array of multiple values, it will return the array
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param mixed $default - if the key is not found, this value will be returned
     * @return mixed|null
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function getVal(string $key, $default = null) {
        return self::getValHelper($this->array, $key, true, $default);
    }

    /**
     * Find $key in the XArray, which is delimited by /
     *
     * If the found value is itself an array of multiple values, it will return the value of array key 0
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param string $default - if the key is not found, this string value will be returned
     * @return string - string representation of value
     */
    public function getString(string $key, string $default = '') : string {
        return self::getStringValue(self::getValHelper($this->array, $key, false, $default));
    }

    /**
     * Set or replace a key value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setVal(string $key, $value = null) : void {
        $this->setValHelper($this->array, $key, $value);

        // array has been mutated, reset key path cache
        $this->keys = null;
    }

    /**
     * Return the array as an XML string
     *
     * @param string|null $outer - optional output tag, used for recursion
     * @return string - xml string representation of the array
     */
    public function toXml(string $outer = null) : string {
        $result = '';
        foreach($this->array as $key => $value) {
            $key = self::getStringValue($key);

            /** @noinspection PhpStatementHasEmptyBodyInspection */
            if(strncmp($key, '@', 1) === 0) {

                // skip attributes
            } else {
                $encodedTag = htmlspecialchars($outer ? $outer : $key, ENT_QUOTES);
                if(is_array($value) && (count($value) > 0) && isset($value[0])) {

                    // numeric array found => child nodes
                    $x = new XArray($value);
                    $result .= $x->toXml($key);
                    unset($x);
                } else {
                    if(is_array($value)) {

                        // attribute list found
                        $attrs = '';
                        foreach($value as $attrKey => $attrValue) {
                            $attrKey = self::getStringValue($attrKey);
                            if(strncmp($attrKey, '@', 1) === 0) {
                                $attrValue = self::getStringValue($attrValue);
                                $attrs .= ' ' . htmlspecialchars(substr($attrKey, 1), ENT_QUOTES) . '="' . htmlspecialchars($attrValue, ENT_QUOTES) . '"';
                            }
                        }
                        $x = new XArray($value);
                        $result .= '<' . $encodedTag . $attrs . '>' . $x->toXml() . '</' . $encodedTag . '>';
                        unset($x);
                    } else {
                        $value = self::getStringValue($value);
                        if($encodedTag !== '#text') {
                            $result .= '<' . $encodedTag . '>' . htmlspecialchars($value, ENT_QUOTES) . '</' . $encodedTag . '>';
                        } else {
                            $result .= htmlspecialchars($value, ENT_QUOTES);
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Accessor for the array
     *
     * @return array
     */
    public function toArray() : array {
        return $this->array;
    }

    /**
     * Returns a collection of mapped key paths to values
     *
     * @return array<string, mixed>
     */
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