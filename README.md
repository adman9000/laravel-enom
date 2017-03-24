# Enom API Wrapper for Laravel

A simple ENOM API Wrapper for Laravel. 

## Install

#### Install via Composer

```
composer require onethirtyone/enom-api
```

Add the following line to your `config/app.php`

```php
'providers' => [
        ...
        onethirtyone\enomapi\EnomServiceProvider::class,
        ...
    ],


 'aliases' => [
        ...
        'Enom' => onethirtyone\enomapi\EnomAPIFacade::class,
    ],
```

