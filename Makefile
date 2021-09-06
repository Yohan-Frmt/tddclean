ifneq (,$(findstring feature-,$(BRANCH)))
	TEMP_NAME=$(subst $(findstring feature-,$(BRANCH)),feature/,$(BRANCH))
else
	TEMP_NAME=$(BRANCH)
endif
ifneq (,$(findstring release-,$(TEMP_NAME)))
	BRANCH_NAME=$(subst $(findstring release-,$(TEMP_NAME)),release/,$(TEMP_NAME))
else
	BRANCH_NAME=$(TEMP_NAME)
endif

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
	psql -d tddclean_test -Upostgres -c "CREATE EXTENSION \"uuid-ossp\";"
	make fixtures-test

prepare-dev:
	make database-dev
	psql -d tddclean_dev -Upostgres -c "CREATE EXTENSION \"uuid-ossp\";"
	make fixtures-dev

prepare-build:
	make database-test
	make fixtures-test
	npm run dev

.PHONY: vendor
analyze:
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

unit-tests:
	php bin/phpunit --testsuite unit --testdox

integration-tests:
	php bin/phpunit --testsuite unit --testdox

system-tests:
	composer database-test
	php bin/phpunit --testsuite unit --testdox

e2e-tests:
	composer database-panther
	php bin/phpunit --testsuite unit --testdox

.PHONY: tests
tests:
	composer database
	php bin/phpunit --testsuite unit,integration,e2e --testdox

install:
	cp .env.dist .env.local
	sed -i -e 's/BRANCH/$(BRANCH)/' .env.local
	sed -i -e 's/USER/$(DATABASE_USER)/' .env.local
	sed -i -e 's/PASSWORD/$(DATABASE_PASSWORD)/' .env.local
	composer install
	npm install
.PHONY: install