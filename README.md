# MonologExtraBundle [![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2FBedrockStreaming%2FMonologExtraBundle%2Fbadge%3Fref%3Dmaster&style=flat)](https://actions-badge.atrox.dev/BedrockStreaming/MonologExtraBundle/goto?ref=master)

Provide extra features for Monolog.

The actual version of this bundle support `Symfony >= 4.4`.
If you need support for older versions, you have to use version `< 3.0`.

## Installation

Via composer :

```json
"require": {
    "m6web/monolog-extra-bundle":"1.1.*"
}
```

then enable the bundle in your kernel:

```php
<?php

$bundles = [
    new M6Web\Bundle\MonologExtraBundle\M6WebMonologExtraBundle
];
```

## Configuration

### Processors

A processor can add, modify or remove log content.

For now, only one processor is available: `ContextInformationProcessor`. It allows you to add extra context information to each log entry.

```yml
m6_web_monolog_extra:
    processors:
        myProcessor:

            # Given that there is only one processor for now,
            # type is optionnal and will have ContextInformation as default value
            type: ContextInformation

            # You can attach the processor to a handler or a channel, but not both
            # Those two configuration entry are optionnal, if you omit both
            # then the processor will be attached to all log channel and handlers.
            handler: gelf
            channel: request

            # Then you can define the context information you wish to add
            # Each entry under config will be an entry in context information
            config:
                foo: bar
                bar: foo
                env: expr(container.getParameter('kernel.environment'))

```