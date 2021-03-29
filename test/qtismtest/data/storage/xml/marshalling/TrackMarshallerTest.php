<?php

namespace qtismtest\data\storage\xml\marshalling;

use qtism\data\content\enums\TrackKind;
use qtism\data\content\xhtml\html5\Track;
use qtism\data\storage\xml\marshalling\MarshallerNotFoundException;
use qtism\data\storage\xml\marshalling\MarshallingException;
use qtism\data\storage\xml\marshalling\UnmarshallingException;

class TrackMarshallerTest extends Html5ElementMarshallerTest
{
    /**
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    public function testMarshallerDoesNotExistInQti21(): void
    {
        $track = new Track('http://example.com/');
        $this->assertHtml5MarshallingOnlyInQti22AndAbove($track, 'track');
    }

    /**
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    public function testMarshall22(): void
    {
        $src = 'http://example.com/';
        $srcLang = 'ja';
        $default = true;
        $kind = 'chapters';

        $expected = sprintf(
            '<track src="%s" srclang="%s" default="%s" kind="%s"/>',
            $src,
            $srcLang,
            $default ? 'true' : 'false',
            $kind
        );

        $track = new Track($src, $srcLang, $default, TrackKind::getConstantByName($kind));

        $this->assertMarshalling($expected, $track);
    }

    /**
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    public function testMarshall22WithDefaultValues(): void
    {
        $src = 'http://example.com/';

        $expected = sprintf('<track src="%s" srclang="en"/>', $src);
        $track = new Track($src);

        $this->assertMarshalling($expected, $track);
    }

    /**
     * @throws MarshallerNotFoundException
     */
    public function testUnMarshallerDoesNotExistInQti21(): void
    {
        $this->assertHtml5UnmarshallingOnlyInQti22AndAbove('<track/>', 'track');
    }

    /**
     * @throws MarshallerNotFoundException
     */
    public function testUnmarshall22(): void
    {
        $src = 'http://example.com/';
        $srcLang = 'ja';
        $default = true;
        $kind = 'chapters';

        $xml = sprintf(
            '<track src="%s" srclang="%s" default="%s" kind="%s"/>',
            $src,
            $srcLang,
            $default ? 'true' : 'false',
            $kind
        );

        $expected = new Track($src, $srcLang, $default, TrackKind::getConstantByName($kind));

        $this->assertUnmarshalling($expected, $xml);
    }

    /**
     * @throws MarshallerNotFoundException
     */
    public function testUnmarshall22WithDefaultValues(): void
    {
        $src = 'http://example.com/';

        $xml = sprintf('<track src="%s"/>', $src);

        $expected = new Track($src);

        $this->assertUnmarshalling($expected, $xml);
    }

    /**
     * @dataProvider WrongXmlToUnmarshall
     * @param string $xml
     * @param string $exception
     * @param string $message
     * @throws MarshallerNotFoundException
     */
    public function testUnmarshallingExceptions(string $xml, string $exception, string $message): void
    {
        $this->assertUnmarshallingException($xml, $exception, $message);
    }

    public function WrongXmlToUnmarshall(): array
    {
        return [
            // TODO: fix Format::isUri because a relative path is a valid URI but not an empty string.
            // ['<track src=" "/>', InvalidArgumentException::class, 'The "src" argument must be a valid URI, " " given.'],

            [
                '<track/>',
                UnmarshallingException::class,
                'Error while unmarshalling element "track": The "src" argument must be a valid URI, "NULL" given.',
            ],
            [
                '<track src=""/>',
                UnmarshallingException::class,
                'Error while unmarshalling element "track": The "src" argument must be a valid URI, "NULL" given.',
            ],
            [
                '<track src="http://example.com/" kind="blah"/>',
                UnmarshallingException::class,
                'Error while unmarshalling element "track": The "kind" argument must be a value from the TrackKind enumeration, "blah" given.',
            ],
        ];
    }
}
