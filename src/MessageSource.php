<?php

declare(strict_types=1);

namespace Yiisoft\Translator\Message\Gettext;

use Yiisoft\Translator\MessageReaderInterface;

final class MessageSource implements MessageReaderInterface
{
    private string $path;
    private array $boundDomains = [];

    public function __construct(string $path)
    {
        if (!file_exists($path) || !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" does not exist.', $path));
        }
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     *
     * We use first parameter as `$n` for plurals if its value type is int.
     */
    public function getMessage(string $id, string $category, string $locale, array $parameters = []): ?string
    {
        $this->bindDomain($category);
        $this->setLocale($locale);
        $n = current($parameters);
        if (is_int($n) === false) {
            return dgettext($category, $id);
        }
        return dngettext($category, $id, $id, $n);
    }

    private function bindDomain(string $category): void
    {
        if (!isset($this->boundDomains[$category])) {
            bindtextdomain($category, $this->path);
        }
    }

    private function setLocale(string $locale): void
    {
        if (!setlocale(LC_ALL, $locale)) {
            throw new \RuntimeException(sprintf('Locale "%s" cannot be set.', $locale));
        }
    }
}
