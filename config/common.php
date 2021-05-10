<?php

declare(strict_types=1);

use Yiisoft\Aliases\Aliases;
use Yiisoft\Factory\Definition\DynamicReference;
use Yiisoft\Translator\MessageReaderInterface;
use Yiisoft\Translator\Message\Gettext\MessageSource;

return [
    MessageReaderInterface::class => [
        'class' => MessageSource::class,
        '__construct()' => [
            DynamicReference::to(fn (Aliases $aliases) => $aliases->get('@message')),
        ],
    ],
];
