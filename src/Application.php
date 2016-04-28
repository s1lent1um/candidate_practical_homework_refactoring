<?php

namespace Language;

use Language\api\Handler;

class Application
{
    /** @var $this */
    protected static $instance;

    /** @var  Handler */
    protected $languageApi;
    
    public function __construct()
    {
        static::$instance or static::$instance = $this;
    }

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return Handler
     */
    public function getLanguageApi()
    {
        if (is_null($this->languageApi)) {
            $this->languageApi = new Handler('system_api', 'language_api');
        }
        return $this->languageApi;
    }

    public function run()
    {
        $languageBatchBo = new LanguageBatchBo();
        $languageBatchBo->generateLanguageFiles();
        $languageBatchBo->generateAppletLanguageXmlFiles();
    }
}
