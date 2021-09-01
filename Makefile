functional-tests:
	php bin/console doctrine:fixtures:load -n --env=test
	php bin/phpunit --testsuite functional --testdox

fixtures-test:
	php bin/console doctrine:fixtures:load -n --env=test

fixtures-dev:
	php bin/console doctrine:fixtures:load -n --env=dev

database-test:
	php bin/console doctrine:database:drop --if-exists --force --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:update --force --env=test

database-dev:
	php bin/console doctrine:database:drop --if-exists --force --env=dev
	php bin/console doctrine:database:create --env=dev
	php bin/console doctrine:schema:update --force --env=dev

prepare-test:
	make database-test
	make fixtures-test

prepare-dev:
	make database-dev
	make fixtures-dev

prepare-build:
	make database-test
	make fixtures-test
	npm run dev

.PHONY: vendor
analyze-windows:
	npm audit --production
	npx eslint assets/
	npx stylelint "assets/styles/**/*.scss"
	composer valid
	composer unused
	php bin/console doctrine:schema:valid --skip-sync
	php bin/phpcs
	php bin/console lint:twig templates/
	vendor\bin\twigcs templates/
	vendor\bin\yaml-lint config/
	php bin/console lint:xliff translations/
	vendor\bin\phpcpd --exclude src/Controller/Admin/ src/
	vendor\bin\phpmd src/ text .phpmd.xml
	php vendor/bin/phpstan analyse -c phpstan.neon -l 7