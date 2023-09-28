<p align="center"><img src="https://i.ytimg.com/vi/q87i6jWGWKs/hqdefault.jpg" height="100"></p>
# Dinesurf

## Inital Build Setup

```bash
# install dependencies
$ composer install
$ npm install

# autoload dependencies
$ composer dump-autoload

# create a .env file in the root of the project, and copy and paste the contents of .env.example into it and save it.
$ cp .env.example .env
$ php artisan key:generate

# Set Your Keys and APi Keys in the .env file.
# Make sure the following keys and values are set before you migrate and seed.
# Paystack Keys.
# Laravel Nova Key.
# AWS Keys.
# Google Keys.

# migrate and seed
$ php artisan migrate --seed

# link your git config file
$ npm run link-git

# start frontend
$ npm run dev

# start server
$ php artisan serve

# And you're good to go!
```

## After Build Setup (Incase of Database Refresh)

```bash
# After you've started the server sometime later in the future during development, if u wish to refresh the database, run
$ php artisan migrate:refresh --seed

# And you're good to go!
```


## Deployment

```bash
# Automatically compile assets for production then automatically commit and push
$ npm run deploy 'your_commit_message'

# And you're good to go!
```

For detailed explanation on how things work, check out [Laravel docs](https://laravel.com).
