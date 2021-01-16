<?php

declare(strict_types=1);

namespace Yiisoft\Translator\Message\Gettext\Tests;

use PHPUnit\Framework\TestCase;
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
    public function testReadWithoutCodepage(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = DIRECTORY_SEPARATOR === '\\' ? 'en-US.UTF-8' : 'en_US.UTF-8';
        $messageSource = new MessageSource(__DIR__ . '/data/locale');

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale, $params));
    }
}
