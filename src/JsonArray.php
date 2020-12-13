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

class JsonArray extends XArray {

    /**
     * @var bool
     */
    private bool $isPrettyPrintEnabled = false;

    /**
     * @var bool
     */
    private bool $isUnescapedSlashesEnabled = false;

    /**
     * @param string $json
     * @return JsonArray
     */
    public static function newJsonArrayFromJson(string $json) : JsonArray {
        return new JsonArray(json_decode($json, true));
    }

    /**
     * @return string
     */
    public function toJson() : string {
        $options = 0;
        if($this->isPrettyPrintEnabled) {
            $options = $options | JSON_PRETTY_PRINT;
        }
        if($this->isUnescapedSlashesEnabled) {
            $options = $options | JSON_UNESCAPED_SLASHES;
        }
        $result = $options > 0 ? json_encode($this->array, $options) : json_encode($this->array);
        return is_string($result) ? $result : '{}';
    }

    /**
     * Remove forward slash escaping with serializing to JSON text
     *
     * @note slashes should always remain escaped if JSON is embedded in, or contains, HTML
     * @return JsonArray
     */
    public function withUnescapedSlashes() : JsonArray {

        // even though this is a clone, we should not create a new array reference - there is no change to the underlying array data
        $instance = clone $this;
        $instance->isUnescapedSlashesEnabled = true;
        return $instance;
    }

    /**
     * Add line spacing and indentation when serializing to JSON text
     *
     * @note Even though this is a clone, we should not create a new array reference - there is no change to the underlying array data
     * @return JsonArray
     */
    public function withPrettyPrint() : JsonArray {

        // even though this is a clone, we should not create a new array reference - there is no change to the underlying array data
        $instance = clone $this;
        $instance->isPrettyPrintEnabled = true;
        return $instance;
    }
}
