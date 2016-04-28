<?php

namespace tests\Language;

use Language\LanguageBatchBo;
use tests\Invoker;

class LanguageBatchBoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return LanguageBatchBo
     */
    protected function getInstance()
    {
        return new LanguageBatchBo();
    }

    public function testGetLanguageCachePath()
    {
        $method = new Invoker($this->getInstance(), 'getLanguageCachePath');

        $prefix = realpath(dirname(dirname(__DIR__))) . '/cache/';
        $apps = [
            'portal',
            'flash',
            'something',
            'something else',
        ];

        foreach ($apps as $app) {
            $this->assertEquals($prefix . $app . '/', $method->invoke($app));
        }
    }
    
    public function testGetAppletLanguages()
    {
        $method = new Invoker($this->getInstance(), 'getAppletLanguages');
        
        $expected = ['en'];
        $this->assertEquals($expected, $method->invoke('applet'));
        $this->assertEquals($expected, $method->invoke('a'));
        $this->assertEquals($expected, $method->invoke('anything'));
    }

    public function testGetAppletLanguageFile()
    {
        $method = new Invoker($this->getInstance(), 'getAppletLanguageFile');
        $result = $method->invoke('anyApplet', 'anyLanguage');
        
        $this->assertInternalType('string', $result);
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $result);
    }
}
