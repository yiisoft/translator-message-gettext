<?php

declare(strict_types=1);

namespace Yiisoft\Translator\Message\Gettext\Tests;

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

    /**
     * @dataProvider generateTranslationsData
     */
    public function testReadWithMapping(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = DIRECTORY_SEPARATOR === '\\' ? 'en-US.UTF-8' : 'en_US.UTF-8';
        $localesMap = ['en-GB-oed' => $locale];

        $messageSource = new MessageSource(__DIR__ . '/data/locale', $localesMap);

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale, $params));
    }

    public function testReadFallback(): void
    {
        $category = 'messages';
        $locale = DIRECTORY_SEPARATOR === '\\' ? 'en-US.UTF-8' : 'en_US.UTF-8';
        $locale_gb = DIRECTORY_SEPARATOR === '\\' ? 'en-IL.UTF-8' : 'en_IL.UTF-8';

        $messageSource = new MessageSource(__DIR__ . '/data/locale');

        $id = 'HELLO_WORLD_UTF8';
        $this->assertEquals('Hello world (UTF-8)', $messageSource->getMessage($id, $category, $locale));
        $this->assertEquals($id, $messageSource->getMessage($id, $category, $locale_gb));

        $id = 'HELLO_WORLD_EN';
        $this->assertEquals('Hello world (EN only)', $messageSource->getMessage($id, $category, $locale));
        $this->assertEquals('Hello world (EN only)', $messageSource->getMessage($id, $category, $locale_gb));

        $id = 'HELLO_WORLD';
        $this->assertEquals('Hello world', $messageSource->getMessage($id, $category, $locale));
        $this->assertEquals('Hello world (EN)', $messageSource->getMessage($id, $category, $locale_gb));
    }

    /**
     * @dataProvider generateTranslationsData
     */
    public function testReadWithoutCodepage(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = 'en_US';
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
