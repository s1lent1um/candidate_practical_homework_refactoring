<?php

namespace tests\Language\api;

use Language\api\Handler;
use Language\Application;
use tests\Invoker;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Handler
     */
    protected function getInstance()
    {
        return Application::getInstance()->getLanguageApi();
    }

    /**
     * @expectedException \Language\api\ExecutionException
     * @expectedExceptionMessage Error during the api call
     */
    public function testValidateResponseNoStatus()
    {
        $method = new Invoker($this->getInstance(), 'validateResponse');
        $method->invoke(['statuS' => 'OK']);
    }

    /**
     * @expectedException \Language\api\UsageException
     * @expectedExceptionMessageRegExp /^Malformed response/
     */
    public function testValidateMalformedResponse()
    {
        $method = new Invoker($this->getInstance(), 'validateResponse');
        $method->invoke(['status' => 'bad']);
    }

    /**
     * @expectedException \Language\api\UsageException
     * @expectedExceptionMessageRegExp /^Wrong response/
     */
    public function testValidateResponseErrorResponse()
    {
        $method = new Invoker($this->getInstance(), 'validateResponse');
        $method->invoke(['status' => 'bad', 'data' => false]);
    }

    /**
     * @expectedException \Language\api\UsageException
     * @expectedExceptionMessageRegExp /^Wrong content/
     */
    public function testValidateResponseBadData()
    {
        $method = new Invoker($this->getInstance(), 'validateResponse');
        $method->invoke(['status' => 'OK', 'data' => false]);
    }

    /**
     * No exception thrown
     */
    public function testValidateResponseOK()
    {
        $method = new Invoker($this->getInstance(), 'validateResponse');
        $method->invoke(['status' => 'OK', 'data' => []]);
    }
}
