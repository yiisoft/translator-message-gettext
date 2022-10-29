<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii Translator gettext Message Storage</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/translator-message-gettext/v/stable.png)](https://packagist.org/packages/yiisoft/translator-message-gettext)
[![Total Downloads](https://poser.pugx.org/yiisoft/translator-message-gettext/downloads.png)](https://packagist.org/packages/yiisoft/translator-message-gettext)
[![Build Status](https://travis-ci.com/yiisoft/translator-message-gettext.svg?branch=master)](https://travis-ci.com/yiisoft/translator-message-gettext)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/translator-message-gettext/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/translator-message-gettext/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/translator-message-gettext/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/translator-message-gettext/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Ftranslator-message-gettext%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/translator-message-gettext/master)
[![static analysis](https://github.com/yiisoft/translator-message-gettext/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/translator-message-gettext/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/translator-message-gettext/coverage.svg)](https://shepherd.dev/github/yiisoft/translator-message-gettext)

The package provides message storage backend based on gettext file format to be used
with [`yiisoft/translator`](https://github.com/yiisoft/translator) package.

## Requirements

- PHP 8.0 or higher.
- `gettext` PHP extension.

## Installation

The package could be installed with composer:

```shell
composer require yiisoft/translator-message-gettext --prefer-dist
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
            DynamicReference::to(fn (Aliases $aliases) => $aliases->get('@message')),
        ],
    ],
];
```

**Note:**  You can use absolute path to translation files in case you don't want to use [`yiisoft/aliases`](https://github.com/yiisoft/aliases)
```php
    MessageReaderInterface::class => [
        'class' => MessageSource::class,
        '__construct()' => [
            '/var/www/app/resourse/messages',
        ],
    ],
```

### Attention

When using gettext, `locale` depends on your OS requirement:
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

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework with
[Infection Static Analysis Plugin](https://github.com/Roave/infection-static-analysis-plugin). To run it:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

## License

The Yii Translator gettext Message Storage is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
