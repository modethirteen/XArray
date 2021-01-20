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

use modethirteen\XArray\Serialization\SerializerInterface;

interface ArrayInterface {

    /**
     * Retrieve all possible key paths in the array
     *
     * @return string[]
     */
    function getKeys() : array;

    /**
     * Find $key in the XArray, which is delimited by /
     *
     * If the found value is itself an array of multiple values, it will return the array
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param mixed $default - if the key is not found, this value will be returned
     * @return mixed|null
     */
    function getVal(string $key, $default = null);

    /**
     * Find $key in the XArray, which is delimited by /
     *
     * If the found value is itself an array of multiple values, it will return the value of array key 0
     *
     * @param string $key - the array path to return, i.e. /pages/content
     * @param string $default - if the key is not found, this string value will be returned
     * @return string - string representation of value
     */
    function getString(string $key, string $default = '') : string;

    /**
     * Set or replace a key value
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    function setVal(string $key, $value = null) : void;

    /**
     * Accessor for the array
     *
     * @return array
     */
    function toArray() : array;

    /**
     * Returns a collection of mapped key paths to values
     *
     * @return array<string, mixed>
     */
    function toFlattenedArray() : array;

    /**
     * Return a collection with the values of another array merged with this one
     *
     * @param ArrayInterface $array
     * @return array
     */
    function toMergedArray(ArrayInterface $array) : array;

    /**
     * @return string
     */
    function toString() : string;

    /**
     * @param SerializerInterface $serializer
     * @return static
     */
    function withSerializer(SerializerInterface $serializer) : object;
}
