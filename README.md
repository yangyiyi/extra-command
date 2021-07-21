# Extra Command to generate Service and Facade for Laravel.

## Requirement
- PHP 7.3 and above.

## Installation

You can install this project using composer, the service provider will be automatically loaded by Laravel itself:

```
composer require yangyiyi/extra-command
```
Once the installation is completed. Run `php artisan` command in your terminal console, and you'll see the new commands `make:service` and `make:facade` under the `make:*` namespace section.

## How to use

### Create Service Only
You may run command below to create service.
```
php artisan make:service Account
```
As example above, it will create the service call `AccountService` under `app\Support\Services`.

### Create Facade Only
You may run command below to create facade.
```
php artisan make:facade Account
```
As example above, it will create the facade call `AccountFacade` under `app\Support\Facades`.

### Create Service / Facade with Options
You may create service, facade, model and migration at the same time by using one of the command below.

#### Create by using make:service / make:facade command
```
- php artisan make:service Account -a
or
- php artisan make:facade Account -a
```
You may found `AccountService` under `app\Support\Services`, `AccountFacade` under `app\Support\Facades`, `Account` under `app\Models` (follow default laravel structure) and `create_account_table` under `database\migration` (follow default laravel structure).

### Options list
```
-a = Generate a migration, facade and model.
-f = Create a facade for the model.
-m = Create a new for the model.
-g = Create a new migration file for the model.
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.