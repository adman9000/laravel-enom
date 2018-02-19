# Enom API Wrapper for Laravel

A simple ENOM API Wrapper for Laravel. 

## Install

#### Install via Composer

```
composer require adman9000/laravel-enom
```

Add the following line to your `config/app.php`

```php
'providers' => [
        ...
        adman9000\enom\EnomServiceProvider::class,
        ...
    ],


 'aliases' => [
        ...
        'Enom' => adman9000\enom\EnomAPIFacade::class,
    ],
```

