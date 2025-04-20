<?php

declare(strict_types=1);

namespace Yiisoft\Translator\Message\Gettext\Tests;

use Closure;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yiisoft\Translator\Message\Gettext\MessageSource;

final class MessageSourceTest extends TestCase
{
    public function generateTranslationsData(): array
    {
        return [
            ['nothing', 'nothing'],
            ['Hello world', 'HELLO_WORLD'],
            ['{n} day(1,21,31)', '{n} day'],
            ['{n} days(0,5,6,7)', '{n} day', ['n' => 0]],
            ['{n} day(1,21,31)', '{n} day', [1]],
            ['{n} days(2,3,4,22)', '{n} day', [2]],
            ['{n} days(0,5,6,7)', '{n} day', [5]],
            ['{n} days(0,5,6,7)', '{n} day', [10]],
            ['{n} day(1,21,31)', '{n} day', [21]],
            ['{n} days(2,3,4,22)', '{n} day', [22]],
            ['{n} days(2,3,4,22)', '{n} day', [22, 'int' => 1]],
            ['{n} day(1,21,31)', '{n} day', ['int' => 1, 2]],
            ['{n} day(1,21,31)', '{n} day', ['int' => 1, 5]],
            ['{n} day(1,21,31)', '{n} day', ['string' => '', 2]],
        ];
    }

    /**
     * @dataProvider generateTranslationsData
     */
    public function testRead(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = DIRECTORY_SEPARATOR === '\\' ? 'en-US.UTF-8' : 'en_US.UTF-8';
        $messageSource = new MessageSource(__DIR__ . '/data/locale');

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale, $params));
    }

    public function generateTranslationsMappingData(): array
    {
        return [
            [
                'en-GB-oed',
                ['en-GB-oed' => DIRECTORY_SEPARATOR === '\\' ? 'en-US.UTF8' : 'en_US.UTF-8'],
                'HELLO',
                'Hello (UTF-8)',
            ],
            [
                'en-GB-oed',
                ['en-GB-oed' => DIRECTORY_SEPARATOR === '\\' ? 'en-US' : 'en_US'],
                'HELLO',
                'Hello (EN_US)',
            ],
            [
                'en-GB-oed',
                ['en-GB-oed' => DIRECTORY_SEPARATOR === '\\' ? 'en-US' : 'en_US'],
                'HELLO_WORLD',
                'Hello world',
            ],
            [
                DIRECTORY_SEPARATOR === '\\' ? 'en-US' : 'en_US',
                [],
                'HELLO_WORLD_UTF8',
                'HELLO_WORLD_UTF8',
            ],
            [
                DIRECTORY_SEPARATOR === '\\' ? 'en-US' : 'en_US',
                null,
                'HELLO_WORLD_UTF8',
                'HELLO_WORLD_UTF8',
            ],
            [
                DIRECTORY_SEPARATOR === '\\' ? 'en-US' : 'en_US',
                static function ($locale) {
                    if (DIRECTORY_SEPARATOR === '\\') {
                        return $locale;
                    }
                    return str_replace('-', '_', explode('.', $locale)[0]);
                },
                'HELLO',
                'Hello (EN_US)',
            ],
            [
                'en-US',
                static function ($locale) {
                    if (DIRECTORY_SEPARATOR === '\\') {
                        return $locale;
                    }
                    return str_replace('-', '_', explode('.', $locale)[0]);
                },
                'HELLO',
                'Hello (EN_US)',
            ],
            [
                'en-US.UTF-8',
                static function ($locale) {
                    if (DIRECTORY_SEPARATOR === '\\') {
                        return $locale;
                    }
                    return str_replace('-', '_', explode('.', $locale)[0]);
                },
                'HELLO',
                'Hello (EN_US)',
            ],
        ];
    }

    /**
     * @dataProvider generateTranslationsMappingData
     */
    public function testReadWithMappingArray(string $locale, array|Closure|null $localesMap, string $id, string $expected): void
    {
        $category = 'messages';
        $messageSource = new MessageSource(__DIR__ . '/data/locale', $localesMap);

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale));
    }

    /**
     * @dataProvider generateTranslationsData
     */
    public function testReadWithoutCodepage(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = DIRECTORY_SEPARATOR === '\\' ? 'en-US' : 'en_US';
        $messageSource = new MessageSource(__DIR__ . '/data/locale');

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale, $params));
    }

    public function testIsDir(): void
    {
        $this->expectException(RuntimeException::class);
        new MessageSource(__DIR__ . 'NOT_EXIST_DIRECTORY');
    }

    public function testNonExistingLocale(): void
    {
        $this->expectException(RuntimeException::class);
        $category = 'messages';
        $locale = 'FAIL_LOCALE';
        $messageSource = new MessageSource(__DIR__ . '/data/locale');

        $this->assertEquals('test', $messageSource->getMessage('test', $category, $locale, []));
    }

    public function testReadMessages(): void
    {
        $this->expectException(RuntimeException::class);

        $category = 'messages';
        $locale = 'FAIL_LOCALE';
        $messageSource = new MessageSource(__DIR__ . '/data/locale');

        $messageSource->getMessages($category, $locale);
    }

    public function testNotExistDirectory(): void
    {
        $directory = __DIR__ . '/non-exists-directory';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Directory "' . $directory . '" does not exist.');
        new MessageSource($directory);
    }
}
