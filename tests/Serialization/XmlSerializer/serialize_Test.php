<?php
/** @noinspection HtmlUnknownAttribute */
declare(strict_types=1);
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
namespace modethirteen\XArray\Tests\Serialization\XmlSerializer;

use modethirteen\XArray\SchemaLockedArray;
use modethirteen\XArray\Serialization\XmlSerializer;
use modethirteen\XArray\Tests\Serialization\SerializerUnitTestCaseBase;
use modethirteen\XArray\XArray;

class serialize_Test extends SerializerUnitTestCaseBase  {

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Simple_array_with_attributes_and_text(string $class) : void {

        // arrange
        $source = ['p' => ['@attr1' => 'val1', '@attr2' => 'val2', '#text' => 'text']];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();
        
        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<p attr1="val1" attr2="val2">text</p>', $result1, 'XML output was incorrect');
        static::assertEquals('<p attr1="val1" attr2="val2">text</p>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Nested_array_with_attributes_and_text(string $class) : void {

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
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<div attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></div>', $result1, 'XML output was incorrect');
        static::assertEquals('<div attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></div>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Nested_array_with_root_element_and_attributes_and_text_and_supplied_root_element(string $class) : void {

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
        $x = self::newArrayFromClass($class, $source);
        $serializer = (new XmlSerializer())
            ->withRootElement('mindtouch');

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<mindtouch attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></mindtouch>', $result1, 'XML output was incorrect');
        static::assertEquals('<mindtouch attr1="val1" attr2="val2">text<p attr4="val4" attr5="val5">text2</p></mindtouch>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Nested_array_with_no_root_element_with_supplied_root_element(string $class) : void {

        // arrange
        $source = [
            'foo' => [
                'bar' => [
                    'qux',
                    'baz'
                ]
            ],
            'qux' => [
                'fred',
                'quxxx'
            ],
            'bar' => 'foo',
            'querty' => true,
            'asdf' => 'zxcv'
        ];

        $x = self::newArrayFromClass($class, $source);
        $serializer = (new XmlSerializer())
            ->withRootElement('xyzzy');

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<xyzzy><foo><bar>qux</bar><bar>baz</bar></foo><qux>fred</qux><qux>quxxx</qux><bar>foo</bar><querty>true</querty><asdf>zxcv</asdf></xyzzy>', $result1, 'XML output was incorrect');
        static::assertEquals('<xyzzy><foo><bar>qux</bar><bar>baz</bar></foo><qux>fred</qux><qux>quxxx</qux><bar>foo</bar><querty>true</querty><asdf>zxcv</asdf></xyzzy>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Attributes_with_special_characters_are_encoded(string $class) : void {

        // arrange
        $source = ['p' => ['@attr1"&\'<>' => 'val1', '#text' => 'text']];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<p attr1&quot;&amp;&#039;&lt;&gt;="val1">text</p>', $result1, 'XML output was incorrect');
        static::assertEquals('<p attr1&quot;&amp;&#039;&lt;&gt;="val1">text</p>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Attribute_values_with_special_characters_are_encoded(string $class) : void {

        // arrange
        $source = ['p' => ['@attr1' => 'val1"&\'<>', '#text' => 'text']];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<p attr1="val1&quot;&amp;&#039;&lt;&gt;">text</p>', $result1, 'XML output was incorrect');
        static::assertEquals('<p attr1="val1&quot;&amp;&#039;&lt;&gt;">text</p>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Text_with_special_characters_are_encoded(string $class) : void {

        // arrange
        $source = ['p' => ['#text' => 'text"&\'<>']];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<p>text&quot;&amp;&#039;&lt;&gt;</p>', $result1, 'XML output was incorrect');
        static::assertEquals('<p>text&quot;&amp;&#039;&lt;&gt;</p>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Xml_tags_with_special_characters_are_encoded(string $class) : void {

        // arrange
        $source = ['p"&\'<>' => ['#text' => 'text']];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<p&quot;&amp;&#039;&lt;&gt;>text</p&quot;&amp;&#039;&lt;&gt;>', $result1, 'XML output was incorrect');
        static::assertEquals('<p&quot;&amp;&#039;&lt;&gt;>text</p&quot;&amp;&#039;&lt;&gt;>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * Checks that the following characters get escaped: ' " < > &
     *
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Xml_values_with_special_characters_are_encoded(string $class) : void {

        // arrange
        $source = ['p' => 'val"&\'<>'];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<p>val&quot;&amp;&#039;&lt;&gt;</p>', $result1, 'XML output was incorrect');
        static::assertEquals('<p>val&quot;&amp;&#039;&lt;&gt;</p>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Can_handle_numeric_arrays(string $class) : void {

        // arrange
        $source = [
            'foo' => [
                'bar', 'baz', 'qux'
            ]
        ];
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        static::assertEquals('<foo>bar</foo><foo>baz</foo><foo>qux</foo>', $result1, 'XML output was incorrect');
        static::assertEquals('<foo>bar</foo><foo>baz</foo><foo>qux</foo>', $result2, 'XML output was incorrect');
        static::assertEquals($result1, $result2);
    }

    /**
     * @dataProvider class_Provider
     * @param string $class
     * @test
     */
    public function Can_handle_non_string_types(string $class) : void {

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
        $x = self::newArrayFromClass($class, $source);
        $serializer = new XmlSerializer();

        // act
        $result1 = $serializer->serialize($x);
        $x = $x->withSerializer($serializer);
        $result2 = $x->toString();

        // assert
        if($class === SchemaLockedArray::class) {

            // schema can not allowlist an integer as a segment of a key path
            static::assertEquals('<div attr1="true" attr2="123">text<p attr4="1.45" attr5="zxcv">text2</p></div>', $result1, 'XML output was incorrect');
            static::assertEquals('<div attr1="true" attr2="123">text<p attr4="1.45" attr5="zxcv">text2</p></div>', $result2, 'XML output was incorrect');
        } else {
            static::assertEquals('<div attr1="true" attr2="123">text<p attr4="1.45" attr5="zxcv">text2</p><541>foo</541></div>', $result1, 'XML output was incorrect');
            static::assertEquals('<div attr1="true" attr2="123">text<p attr4="1.45" attr5="zxcv">text2</p><541>foo</541></div>', $result2, 'XML output was incorrect');
        }
        static::assertEquals($result1, $result2);
    }
}
