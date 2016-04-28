<?php

namespace tests\Language;

use Language\Config;
use Language\FileWriter;

class FileWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCachePath()
    {
        $prefix = realpath(dirname(dirname(__DIR__))) . '/cache/';
        $apps = [
            'portal',
            'flash',
            'something',
            'something else',
        ];

        foreach ($apps as $app) {
            $writer = new FileWriter(new Config(), $app);
            $this->assertEquals($prefix . $app . '/', $writer->getCachePath());
        }
    }

    /**
     * @depends testGetCachePath
     */
    public function testGetDestination()
    {
        $prefix = realpath(dirname(dirname(__DIR__))) . '/cache/';
        $app = 'something';
        $languages = ['en', 'ru', 'elvish'];

        foreach ($languages as $language) {
            $writer = new FileWriter(new Config(), $app);
            $this->assertEquals($prefix . $app . '/' . $language . '.php', $writer->getDestination($language));
        }
    }
}
