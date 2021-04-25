<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
</p>
<h1 align="center">Translator gettext message storage</h1>

The package provides message storage backend based on gettext file format to be used with `yiisoft/translator` package.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/translator-message-gettext/v/stable.png)](https://packagist.org/packages/yiisoft/translator-message-gettext)
[![Total Downloads](https://poser.pugx.org/yiisoft/translator-message-gettext/downloads.png)](https://packagist.org/packages/yiisoft/translator-message-gettext)
[![Build Status](https://travis-ci.com/yiisoft/translator-message-gettext.svg?branch=master)](https://travis-ci.com/yiisoft/translator-message-gettext)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/translator-message-gettext/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/translator-message-gettext/?branch=master)

## Installation

The preferred way to install this package is through [Composer](https://getcomposer.org/download/):
```bash
composer require yiisoft/translator-message-gettext
```

## Configuration

In case you use [`yiisoft/config`](http://github.com/yiisoft/config), you will get configuration automatically. If not, the following DI container configuration is necessary:

```php
<?php

declare(strict_types=1);

use Yiisoft\Translator\MessageReaderInterface;
use Yiisoft\Translator\Message\Gettext\MessageSource;
use Yiisoft\Aliases\Aliases;

return [
    MessageReaderInterface::class => [
        'class' => MessageSource::class,
        '__construct()' => [
            fn (Aliases $aliases) => $aliases->get('@message'),
        ],
    ],
];
```

**Note:**  You can use absolutely path to translation files, if you not use [`yiisoft/aliases`](https://github.com/yiisoft/aliases)
```php
    MessageReaderInterface::class => [
        'class' => MessageSource::class,
        '__construct()' => [
            '/var/www/app/resourse/messages',
        ],
    ],
```

### Attention
On using gettext, you need configure `locale` for package yiisoft/translator according to your OS requirement:
* for Windows `'locale' => 'en-US.UTF-8'`
* for Linux  `'locale' => 'en_US.UTF-8'`

## General usage

### Create of instance of MessageSource
```php
/** @var string $path - path to your gettext storage */
$messageSource = new \Yiisoft\Translator\Message\Gettext\MessageSource($path);
```

### Read message without `yiisoft/translator` package
```php
/** 
 * @var \Yiisoft\Translator\Message\Gettext\MessageSource $messageSource
 * @var ?string $translatedString
 */
$id = 'messageIdentificator';
$category = 'messages';
$language = 'de-DE';

$translatedString = $messageSource->getMessage($id, $category, $language);
```

You can create your translations in .mo format with use third-party software (for example Poedit)

Recommended structure to your gettext storage:
```
-- path_to_your_storage
  -- de_DE
    -- LC_MESSAGES
        -- messages.mo
```

## Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

### Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Translator PHP message storage is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
