# LaravelRelationMaker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hsuan/laravel-relation-maker.svg?style=flat-square)](https://packagist.org/packages/hsuan/laravel-relation-maker)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/hsuan1117/laravel-relation-maker/run-tests?label=tests)](https://github.com/hsuan/laravel-relation-maker/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/hsuan1117/laravel-relation-maker/Check%20&%20fix%20styling?label=code%20style)](https://github.com/hsuan/laravel-relation-maker/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hsuan/laravel-relation-maker.svg?style=flat-square)](https://packagist.org/packages/hsuan/laravel-relation-maker)

The package can help you generate model, migrations for eloquent relationship.
For example, ```php artisan make:relation Food hasMany Cookie```

## Installation

You can install the package via composer:

```bash
composer require hsuan/laravel-relation-maker --dev
```

You can publish the stubs with:
```bash
php artisan vendor:publish --provider="Hsuan\LaravelRelationMaker\LaravelRelationMakerServiceProvider" --tag="laravel-relation-maker-stubs"
```

## Usage

```php
php artisan make:relation {modelA} {relationship} {modelB}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Hsuan](https://github.com/hsuan1117)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
