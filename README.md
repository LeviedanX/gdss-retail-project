## Set-up

1. Clone this repo & `cd` into it;
2. Run `composer install`;
3. Start server with `php artisan serve`

If `composer install` fails saying it requires `ext-iconv`, install `php-iconv` with

```
$ sudo apt install php-iconv
```

or open your `php.ini` file in an editor, search for `extension=iconv` and uncomment it. Then run `php -m | grep iconv`, if it prints `iconv` then we're all set. Run `composer install` again, wait, and `php artisan serve`.
