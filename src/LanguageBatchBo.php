<?php

namespace Language;

use Language\api\Exception as ApiException;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
    /**
     * Contains the applications which ones require translations.
     *
     * @var array
     */
    protected static $applications = array();

    /** @var Application */
    protected $app;

    public function __construct(Application $app = null)
    {
        $this->app = $app ?: Application::getInstance();
    }

    /**
     * Starts the language file generation.
     *
     * @throws RuntimeException
     * @return void
     */
    public function generateLanguageFiles()
    {
        // The applications where we need to translate.
        self::$applications = Config::get('system.translated_applications');

        echo "\nGenerating language files\n";
        foreach (self::$applications as $application => $languages) {
            echo "[APPLICATION: " . $application . "]\n";
            foreach ($languages as $language) {
                echo "\t[LANGUAGE: " . $language . "]";
                $this->getLanguageFile($application, $language);
                echo " OK\n";
            }
        }
    }

    /**
     * Gets the language file for the given language and stores it.
     *
     * @param string $application The name of the application.
     * @param string $language The identifier of the language.
     *
     * @throws RuntimeException   If there was an error during the download of the language file.
     *
     * @return bool   The success of the operation.
     */
    protected function getLanguageFile($application, $language)
    {
        try {
            $languageResponse = $this->app->getLanguageApi()
                ->getSystemHandler('LanguageFiles')
                ->call('getLanguageFile', ['language' => $language]);
        } catch (ApiException $previous) {
            throw new RuntimeException(
                "Error during getting language file: ({$application}/{$language})",
                0,
                $previous
            );
        }

        $writer = new FileWriter($this->app->getConfig(), $application);
        $writer->write($language, $languageResponse);
    }


    /**
     * Gets the language files for the applet and puts them into the cache.
     *
     * @throws RuntimeException   If there was an error.
     */
    public function generateAppletLanguageXmlFiles()
    {
        // List of the applets [directory => applet_id].
        $applets = array(
            'memberapplet' => 'JSM2_MemberApplet',
        );

        echo "\nGetting applet language XMLs..\n";

        foreach ($applets as $appletDirectory => $appletLanguageId) {
            echo " Getting > $appletLanguageId ($appletDirectory) language xmls..\n";
            $languages = $this->getAppletLanguages($appletLanguageId);
            if (empty($languages)) {
                throw new RuntimeException("There is no available languages for the {$appletLanguageId} applet.");
            }

            echo ' - Available languages: ' . implode(', ', $languages) . "\n";
            $writer = new FileXmlWriter($this->app->getConfig(), 'flash');
            foreach ($languages as $language) {
                $xmlContent = $this->getAppletLanguageFile($appletLanguageId, $language);
                $writer->write($language, $xmlContent);
                echo " OK saving " . $writer->getDestination($language) . " was successful.\n";
            }
            echo " < $appletLanguageId ($appletDirectory) language xml cached.\n";
        }

        echo "\nApplet language XMLs generated.\n";
    }

    /**
     * Gets the available languages for the given applet.
     *
     * @param string $applet The applet identifier.
     *
     * @return array   The list of the available applet languages.
     * @throws RuntimeException
     */
    protected function getAppletLanguages($applet)
    {
        try {
            return $this->app->getLanguageApi()
                ->getSystemHandler('LanguageFiles')
                ->call('getAppletLanguages', ['applet' => $applet]);
        } catch (ApiException $previous) {
            throw new RuntimeException(
                "Getting languages for applet ({$applet}) failed:\n{$previous->getMessage()}",
                0,
                $previous
            );
        }
    }


    /**
     * Gets a language xml for an applet.
     *
     * @param string $applet The identifier of the applet.
     * @param string $language The language identifier.
     *
     * @return string|false   The content of the language file or false if weren't able to get it.
     * @throws RuntimeException
     */
    protected function getAppletLanguageFile($applet, $language)
    {
        try {
            return $this->app->getLanguageApi()
                ->getSystemHandler('LanguageFiles')
                ->call('getAppletLanguageFile', ['applet' => $applet, 'language' => $language]);
        } catch (ApiException $previous) {
            throw new RuntimeException(
                "Getting language ('{$language}') xml for applet ({$applet}) failed:\n{$previous->getMessage()}",
                0,
                $previous
            );
        }
    }
}
