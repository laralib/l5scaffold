# Laravel 5 Scaffold Generators


Hi, this is a scaffold generator for Laravel 5.



## Usage

### Step 1: Install Through Composer

```
composer require 'laralib/l5scaffold' --dev
```

### Step 2: Add the Service Provider

Open `config/app.php` and, to your "providers" array at the bottom, add:

```
"Laralib\L5scaffold\GeneratorsServiceProvider"
```

### Step 3: Run Artisan!

You're all set. Run `php artisan` from the console, and you'll see the new commands `make:scaffold`.

## Examples


```
php artisan make:scaffold Tweet --schema="title:string, body:text"
```

## Scaffold
![image](http://i62.tinypic.com/11maveb.png)
