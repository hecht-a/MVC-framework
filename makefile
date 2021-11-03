local:
	php -S localhost:8000 -t public/

network:
	php -S 0.0.0.0:8000 -t public/

migrate:
	php migrations.php

compile:
	sass public/scss:public/css --watch