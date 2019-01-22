<?php declare(strict_types=1);
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
namespace MindTouch\XArray;

/**
 * Class XArray - get/set accessors for arrays
 *
 * @package MindTouch\XArray
 */
class XArray {

    /**
     * Get string representation of value
     *
     * @param mixed $value
     * @return string
     */
    private static function getStringValue($value) : string {
        if(is_bool($value)) {
            return $value === true ? 'true' : 'false';
        }
        return !is_string($value) ? strval($value) : $value;
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
            if($i == $count) {
                if($value === null) {
                    unset($array[$key]);
                    return;
                }
                $array[$key] = $value;
            } else if(!isset($array[$key])) {
                $array[$key] = [];
            }
            if(is_string($array[$key])) {
                return;
            }
            $array = &$array[$key];
        }
    }

    /**
     * @var array
     */
    protected $array = [];

    /**
     * @param array $array - array values to create XArray from. If not supplied, XArray will start empty
     */
    public function __construct(array $array = null) { $this->array = $array !== null ? $array : []; }

    /**
     * Find $key in the XArray, which is delimited by /
     * If the found value is itself an array of multiple values, the array is returned.
     * If the found value is a single value, it is wrapped in an array then returned.
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param array $default - if the key is not found, this array will be returned
     * @return array|null
     */
    public function getAll(string $key = '', array $default = []) : ?array {
        $array = $this->array;
        if($key === '') {
            return $array;
        }
        $keys = explode('/', $key);
        $count = count($keys);
        $i = 0;
        foreach($keys as $val) {
            $i++;
            if($val === '') {
                continue;
            }
            if(!isset($array[$val])) {
                return $default;
            }
            if(!is_array($array[$val])) {
                return [$array[$val]];
            }
            $array = $array[$val];
            if($i == $count) {
                if(key($array) !== 0) {
                    $array = [$array];
                }
            }
        }
        return $array;
    }

    /**
     * Find $key in the XArray, which is delimited by /
     * If the found value is itself an array of multiple values, it will return the value of array key 0.
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param mixed $default - if the key is not found, this value will be returned
     * @return mixed|null
     */
    public function getVal(string $key, $default = null) {
        $array = $this->array;
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
            if(is_array($array) && key($array) === 0) {
                $array = current($array);
            }
        }
        return $array;
    }

    /**
     * Find $key in the XArray, which is delimited by /
     * If the found value is itself an array of multiple values, it will return the value of array key 0
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param string $default - if the key is not found, this string value will be returned
     * @return string - string representation of value
     */
    public function getString(string $key, string $default = '') : string {
        return self::getStringValue($this->getVal($key, $default));
    }

    /**
     * Set or replace a key value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setVal(string $key, $value = null) : void { $this->setValHelper($this->array, $key, $value); }

    /**
     * Return the array as an XML string
     *
     * @param string $outer - optional output tag, used for recursion
     * @return string - xml string representation of the array
     */
    public function toXml(string $outer = null) : string {
        $result = '';
        foreach($this->array as $key => $value) {
            $key = self::getStringValue($key);
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
    public function toArray() : array { return $this->array; }
}