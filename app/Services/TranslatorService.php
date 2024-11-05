<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslatorService
{
    protected $translator;

    public function __construct($targetLang = 'en')
    {
        $this->translator = new GoogleTranslate();
        $this->translator->setTarget($targetLang);
    }

    public function translate($text)
    {
        return $this->translator->translate($text);
    }
}
