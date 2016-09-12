# Todo list

To install the app:

1. Create a database.
2. Copy .env.example to .env and edit the database information as needed

Run the following commands in a shell window:

`composer install
php artisan migrate
npm install
bower install
gulp`

If you want to run the tests, modify the $baseUrl in /tests/TestCase.php to point to the correct location of this app URL, then run

`./vendor/bin/phpunit`

from a shell window.

