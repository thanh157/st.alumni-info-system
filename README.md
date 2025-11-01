## Requirements

- PHP >= 8.3.0

## Usage

1. Clone project
2. Create .env file, copy content from .env.example to .env file and config your database in .env:

 

3. Run

``` bash
	$ composer install
	$ php artisan key:generate
	$ php artisan migrate
	$ php artisan db:seed --class=DatabaseSeeder
	$ php artisan scribe:generate
	$ php artisan storage:link
	$ php artisan route:clear
	$ php artisan config:clear
	
	
	$ npm install

```

4. Local development server

- Run

``` bash
back-end
	$ php artisan serve
	
 
