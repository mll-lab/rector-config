# rector-config

Shared configuration for rector

[![GitHub license](https://img.shields.io/github/license/mll-lab/rector-config.svg)](https://github.com/mll-lab/rector-config/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/mll-lab/rector-config.svg)](https://packagist.org/packages/mll-lab/rector-config)
[![Packagist](https://img.shields.io/packagist/dt/mll-lab/rector-config.svg)](https://packagist.org/packages/mll-lab/rector-config)

## Installation

    composer require --dev mll-lab/rector-config

## Usage

In your `rector.php`:

```diff
+use function MLL\RectorConfig\config;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
+   config($rectorConfig);

    $rectorConfig->paths([
        ...
    ]);
    $rectorConfig->rule(...);
};
```

If the project is using Laravel, use `laravel` instead of `config`.
