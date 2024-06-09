<?php

namespace App\Factory;

use App\Data\Constants\Translation;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatorTrait
{
    protected TranslatorInterface $translator;

    #[Required]
    public function setTranslator(TranslatorInterface $translator): static
    {
        $this->translator = $translator;

        return $this;
    }

    protected function getDefaultDomainApp(): string
    {
        return Translation::APP_DOMAIN;
    }

    protected function translate(string $keywords, array $parameters = [], string $domain = null): string
    {
        if(null === $domain){
            $domain = $this->getDefaultDomainApp();
        }

        return $this->translator->trans($keywords, $parameters, $domain);
    }
}