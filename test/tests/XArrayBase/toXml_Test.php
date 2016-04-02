<?php
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
namespace MindTouch\XArray\test\tests\XArrayBase;

abstract class toXml_Test extends XArrayUnitTestCaseBase  {

    /**
     * @test
     */
    public function Simple_array_with_attributes_and_text() {

        // arrange
        $source = ['p' => ['@attr1' => 'val1', '@attr2' => 'val2', '#text' => 'text']];
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<p attr1="val1" attr2="val2">text</p>', $xml, 'XML output was incorrect');
    }

    /**
     * @test
     */
    public function Nested_array_with_attributes_and_text() {

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
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<div attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></div>', $xml, 'XML output was incorrect');
    }

    /**
     * @test
     */
    public function Nested_array_with_attributes_and_text_and_outer_xml() {
        
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
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml('mindtouch');

        // assert
        $this->assertEquals('<mindtouch attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></mindtouch>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Attributes_with_special_characters_are_encoded() {

        // arrange
        $source = ['p' => ['@attr1"&\'<>' => 'val1', '#text' => 'text']];
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<p attr1&quot;&amp;&#039;&lt;&gt;="val1">text</p>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Attribute_values_with_special_characters_are_encoded() {

        // arrange
        $source = ['p' => ['@attr1' => 'val1"&\'<>', '#text' => 'text']];
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<p attr1="val1&quot;&amp;&#039;&lt;&gt;">text</p>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Text_with_special_characters_are_encoded() {

        // arrange
        $source = ['p' => ['#text' => 'text"&\'<>']];
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<p>text&quot;&amp;&#039;&lt;&gt;</p>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Xml_tags_with_special_characters_are_encoded() {

        // arrange
        $source = ['p"&\'<>' => ['#text' => 'text']];
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<p&quot;&amp;&#039;&lt;&gt;>text</p&quot;&amp;&#039;&lt;&gt;>', $xml, 'XML output was incorrect');
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @test
     */
    public function Xml_values_with_special_characters_are_encoded() {

        // arrange
        $source = ['p' => 'val"&\'<>'];
        $Array = $this->newXArray($source);

        // act
        $xml = $Array->toXml();

        // assert
        $this->assertEquals('<p>val&quot;&amp;&#039;&lt;&gt;</p>', $xml, 'XML output was incorrect');
    }
}