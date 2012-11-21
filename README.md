# MongoValidator: Laravel Validation for MongoDB

MongoValidator implements the `unique` and `exists` [Laravel validation rules](http://laravel.com/docs/validation#rule-unique) for MongoDB.

## Installation

### Artisan

	php artisan bundle:install mongovalidator

### Bundle Registration

Add the following to your **application/bundles.php** file:

```php
'mongovalidator' => array('auto' => true),
```

Add your MongoDB connection details to your **application/config/database.php** file:
```php
'connections' => array(
	
	...
	
	'mongo' => array(
		'hostname'   => 'localhost',
		'db'         => 'jemem'
	)
)
```

