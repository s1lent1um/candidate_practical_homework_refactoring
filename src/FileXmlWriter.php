<?php

namespace Language;

class FileXmlWriter extends FileWriter
{
    /**
     * @param $language
     * @return string
     */
    public function getDestination($language)
    {
        return $this->getCachePath() . 'lang_' . $language . '.xml';
    }
}
