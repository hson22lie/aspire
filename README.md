
## About Project

- for running this app you need to have php 8.0 at least
- for first please run composer install
- and please run : cp .env.example into .env
- please create up 2 database, 1 for testing and 1 for the main app, for this project, we are going to use MySQL
- set up the env with the correct db that you use
- please run : php artisan migrate and then run : php artisan migrate --database=mysql_testing
- please run : php artisan db:seed --class=AdminSeeder to seed and admin user
- please run this to migrate seeder into db testing php artisan db:seed --class=TestDataSeeder --database=mysql_testing