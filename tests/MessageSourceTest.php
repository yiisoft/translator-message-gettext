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
            ['%s day(1,21,31)', '%s day'],
            ['%s days(0,5,6,7)', '%s day', ['n' => 0]],
            ['%s day(1,21,31)', '%s day', [1]],
            ['%s days(2,3,4,22)', '%s day', [2]],
            ['%s days(0,5,6,7)', '%s day', [5]],
            ['%s days(0,5,6,7)', '%s day', [10]],
            ['%s day(1,21,31)', '%s day', [21]],
            ['%s days(2,3,4,22)', '%s day', [22]],
        ];
    }

    /**
     * @dataProvider generateTranslationsData
     */
    public function testRead(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = 'en_US.utf8';
        $messageSource = new MessageSource(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'locale');

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale, $params));
    }

    /**
     * @dataProvider generateTranslationsData
     */
    public function testReadWithoutCodepage(string $expected, string $id, array $params = []): void
    {
        $category = 'messages';
        $locale = 'en_US';
        $messageSource = new MessageSource(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'locale');

        $this->assertEquals($expected, $messageSource->getMessage($id, $category, $locale, $params));
    }
}
