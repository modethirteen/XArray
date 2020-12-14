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

use modethirteen\XArray\SchemaLockedArray;

abstract class toXml_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     */
    public function Simple_array_with_attributes_and_text() : void {

        // arrange
        $source = ['p' => ['@attr1' => 'val1', '@attr2' => 'val2', '#text' => 'text']];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<p attr1="val1" attr2="val2">text</p>', $xml, 'XML output was incorrect');
    }

    /**
     * @test
     */
    public function Nested_array_with_attributes_and_text() : void {

        // arrange
        $source = [
            'div' => [
                '@attr1' => 'val1',
                '@attr2' => 'val2',
                '#text' => 'text',
                'p' => [
                    '@attr4' => 'val4',
                    '@attr5' => 'val5',
                    '#text' => 'text2'
                ]
            ]
        ];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<div attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></div>', $xml, 'XML output was incorrect');
    }

    /**
     * @test
     */
    public function Nested_array_with_attributes_and_text_and_outer_xml() : void {
        
        // arrange
        $source = [
            'div' => [
                '@attr1' => 'val1',
                '@attr2' => 'val2',
                '#text' => 'text',
                'p' => [
                    '@attr4' => 'val4',
                    '@attr5' => 'val5',
                    '#text' => 'text2'
                ]
            ]
        ];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml('mindtouch');

        // assert
        $this->assertEquals('<mindtouch attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></mindtouch>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Attributes_with_special_characters_are_encoded() : void {

        // arrange
        $source = ['p' => ['@attr1"&\'<>' => 'val1', '#text' => 'text']];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<p attr1&quot;&amp;&#039;&lt;&gt;="val1">text</p>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Attribute_values_with_special_characters_are_encoded() : void {

        // arrange
        $source = ['p' => ['@attr1' => 'val1"&\'<>', '#text' => 'text']];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<p attr1="val1&quot;&amp;&#039;&lt;&gt;">text</p>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Text_with_special_characters_are_encoded() : void {

        // arrange
        $source = ['p' => ['#text' => 'text"&\'<>']];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<p>text&quot;&amp;&#039;&lt;&gt;</p>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Xml_tags_with_special_characters_are_encoded() : void {

        // arrange
        $source = ['p"&\'<>' => ['#text' => 'text']];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<p&quot;&amp;&#039;&lt;&gt;>text</p&quot;&amp;&#039;&lt;&gt;>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Xml_values_with_special_characters_are_encoded() : void {

        // arrange
        $source = ['p' => 'val"&\'<>'];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        $this->assertEquals('<p>val&quot;&amp;&#039;&lt;&gt;</p>', $xml, 'XML output was incorrect');
    }

    /**
     * @test
     */
    public function Can_handle_numeric_arrays() : void {
    
        // arrange
        $source = ['foo' => [
            'bar', 'baz', 'qux'    
        ]];
        $x = $this->newXArray($source);
        
        // act
        $xml = $x->toXml();
        
        // assert
        $this->assertEquals('<foo>bar</foo><foo>baz</foo><foo>qux</foo>', $xml, 'XML output was incorrect');
    }

    /**
     * @test
     */
    public function Can_handle_non_string_types() : void {

          // arrange
        $source = [
            'div' => [
                '@attr1' => true,
                '@attr2' => 123,
                '#text' => 'text',
                'p' => [
                    '@attr4' => 1.45,
                    '@attr5' => new class {
                        public function __toString() : string {
                            return 'zxcv';
                        }
                    },
                    '#text' => 'text2'
                ],
                541 => [
                    '#text' => 'foo'
                ]
            ]
        ];
        $x = $this->newXArray($source);

        // act
        $xml = $x->toXml();

        // assert
        if(static::$class === SchemaLockedArray::class) {

            // schema can not allowlist an integer as a segment of a key path
            $this->assertEquals('<div attr1="true" attr2="123">text<p attr4="1.45" attr5="zxcv">text2</p></div>', $xml, 'XML output was incorrect');
        } else {
            $this->assertEquals('<div attr1="true" attr2="123">text<p attr4="1.45" attr5="zxcv">text2</p><541>foo</541></div>', $xml, 'XML output was incorrect');
        }
    }
}