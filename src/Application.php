<?php

namespace Language;

use Language\api\Handler;

class Application
{
    /** @var $this */
    protected static $instance;

    /** @var  Handler */
    protected $languageApi;

    /** @var Config  */
    protected $config;
    
    public function __construct()
    {
        static::$instance or static::$instance = $this;
        $this->config = new Config();
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

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function run()
    {
        // Controller abstraction doesn't seem necessary right now
        $languageBatchBo = new LanguageBatchBo();
        $languageBatchBo->generateLanguageFiles();
        $languageBatchBo->generateAppletLanguageXmlFiles();
    }
}
