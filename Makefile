install:

	composer install

gendiff:

	./bin/gendiff -h

validate:

	composer validate

lint:

	composer run-script phpcs -- --standard=PSR12 src bin
	