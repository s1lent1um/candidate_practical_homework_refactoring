<?php

namespace Language;

class FileWriter
{
    /** @var Config */
    protected $config;
    /** @var string */
    protected $application;

    public function __construct($config, $application)
    {
        assert(is_callable([$config, 'get']));
        $this->config = $config;
        $this->application = $application;
    }

    /**
     * Gets the directory of the cached language files.
     *
     * @return string   The directory of the cached language files.
     */
    public function getCachePath()
    {
        return $this->config->get('system.paths.root') . '/cache/' . $this->application . '/';
    }

    /**
     * @param $language
     * @return string
     */
    public function getDestination($language)
    {
        return $this->getCachePath() . $language . '.php';
    }

    /**
     * @param $language
     * @param $content
     * @throws RuntimeException
     */
    public function write($language, $content)
    {
        $destination = $this->getDestination($language);
        // If there is no folder yet, we'll create it.
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $written = file_put_contents($destination, $content);
        if ($written !== strlen($content)) {
            throw new RuntimeException("Unable to write language file to: {$destination}");
        }
    }
}
