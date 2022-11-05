<?php

declare(strict_types=1);

namespace Yiisoft\Translator\Message\Gettext;

use RuntimeException;
use Yiisoft\Translator\MessageReaderInterface;

use function is_int;

final class MessageSource implements MessageReaderInterface
{
    private array $boundDomains = [];

    /**
     * @param string $path The directory path.
     */
    public function __construct(
        private string $path
    ) {
        if (!is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" does not exist.', $path));
        }
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

    public function getMessages(string $category, string $locale): array
    {
        throw new RuntimeException('Unable to get all messages from gettext.');
    }

    private function bindDomain(string $category): void
    {
        if (!isset($this->boundDomains[$category])) {
            /** @psalm-suppress UnusedFunctionCall */
            bindtextdomain($category, $this->path);
        }
    }

    private function setLocale(string $locale): void
    {
        if (!setlocale(LC_ALL, $locale)) {
            throw new RuntimeException(sprintf('Locale "%s" cannot be set.', $locale));
        }
    }
}
