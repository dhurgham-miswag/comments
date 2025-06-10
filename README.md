# Laravel Comments Package

A Laravel package for managing comments with Livewire integration. This package provides a flexible and easy-to-use commenting system that can be attached to any model in your Laravel application.

## Features

- Comment on any model using polymorphic relationships
- Nested replies support
- Livewire integration for real-time updates
- Customizable user model configuration
- Easy to install and configure
- Clean and modern UI

## Installation

You can install the package via composer:

```bash
composer require dhurgham-miswag/comments
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="comments-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="comments-config"
```

This is the contents of the published config file:

```php
return [
    // Comment validation settings
    'validation' => [
        // Minimum length required for a comment
        'min_length' => 3,
    ],

    // Whether to allow replies to comments
    'can_reply' => true,

    // User model configuration
    'user_model' => [
        'f_key' => 'user_id', // Foreign key in comments table
        'p_key' => 'user_id', // Primary key in users table
    ],
];
```

Optionally, you can publish the views using:

```bash
php artisan vendor:publish --tag="comments-views"
```

## Usage

Add the comments component to your blade view:

```php
<livewire:comments :model-type="YourModel::class" :model-id="$model->id" />
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dhurgham Miswag](https://github.com/dhurgham-miswag)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
