<?php

namespace tests\Language;

use Language\Config;
use Language\FileXmlWriter;

class FileXmlWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @depends tests\Language\FileWriterTest::testGetCachePath
     */
    public function testGetDestination()
    {
        $prefix = realpath(dirname(dirname(__DIR__))) . '/cache/';
        $app = 'something';
        $languages = ['en', 'ru', 'elvish'];

        foreach ($languages as $language) {
            $writer = new FileXmlWriter(new Config(), $app);
            $this->assertEquals($prefix . $app . '/lang_' . $language . '.xml', $writer->getDestination($language));
        }
    }
}
