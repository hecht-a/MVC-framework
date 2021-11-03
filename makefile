serve:
	php -S localhost:8000 -t public/

migrate:
	php migrations.php