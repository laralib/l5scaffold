# Laravel 5 Scaffold Generator


Hi, this is a scaffold generator for Laravel 5.



## Usage

### Step 1: Install Through Composer

```
composer require 'laralib/l5scaffold' --dev
```

### Step 2: Add the Service Provider

Open `config/app.php` and, to your **providers** array at the bottom, add:

```
"Laralib\L5scaffold\GeneratorsServiceProvider"
```

### Step 3: Run Artisan!

You're all set. Run `php artisan` from the console, and you'll see the new commands `make:scaffold`.

## Examples


```
php artisan make:scaffold Tweet --schema="title:string:default('Tweet #1'), body:text"
```
This command will generate:

```
app/Tweet.php
app/Http/Controllers/TweetController.php
database/migrations/2015_04_23_234422_create_tweets_table.php
database/seeds/TweetTableSeeder.php
resources/views/layout.blade.php
resources/views/tweets/index.blade.php
resources/views/tweets/show.blade.php
resources/views/tweets/edit.blade.php
resources/views/tweets/create.blade.php
```
And don't forget to run:

```
php artisan migrate
```


## Scaffold
![image](http://i62.tinypic.com/11maveb.png)
![image](http://i58.tinypic.com/eqchat.png)
![image](http://i62.tinypic.com/20h7k8n.png)
