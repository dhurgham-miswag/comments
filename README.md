# Laravel Comments Package

A Laravel package for managing comments with Livewire integration. This package provides a flexible and easy-to-use commenting system that can be attached to any model in your Laravel application.

## Features

- Comment on any model using polymorphic relationships
- Nested replies support
- Livewire integration for real-time updates
- Filament rich text editor integration for enhanced content editing
- Customizable user model configuration
- Easy to install and configure
- Clean and modern UI with Tailwind CSS
- Support for HTML content in comments
- Configurable comment display options

## Package Updates

This package is automatically updated on Packagist when new versions are pushed to GitHub. To ensure you always get the latest version:

1. Add the package to your `composer.json`:
```json
{
    "require": {
        "dhurgham-miswag/comments": "^0.1"
    }
}
```

2. Run `composer update` to get the latest version.

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

    // Whether to show commenter names
    'can_show_commentor_name' => true,

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

## Requirements

- PHP 8.0 or higher
- Laravel 8.0 or higher
- Livewire 2.0 or higher
- Filament 2.0 or higher (for rich text editor)

## Usage

Add the comments component to your blade view:

```php
<livewire:comments :model-type="YourModel::class" :model-id="$model->id" />
```

### Features

#### Rich Text Editor
The package uses Filament's rich text editor for both comments and replies, providing:
- Text formatting (bold, italic, etc.)
- Lists (ordered and unordered)
- Links
- Images
- Code blocks
- Tables

#### Comment Display
- Nested replies with indentation
- User avatars with initials
- Relative timestamps
- HTML content support
- Configurable commenter name display

#### Configuration Options
- Enable/disable replies
- Show/hide commenter names
- Customize user model relationships
- Set minimum comment length
- Customize validation rules

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
