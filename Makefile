# Variables
COMPOSE=docker-compose
PHP_CONTAINER=home_budget_app_php

# Commands
app.build:
	$(COMPOSE) build

app.up:
	$(COMPOSE) up -d --build
	make app.run_migrations:

app.down:
	$(COMPOSE) down

app.restart:
	make app.down
	make app.up

app.connect:
	docker exec -it -u root $(PHP_CONTAINER) sh

app.logs:
	$(COMPOSE) logs -f

app.run_migrations:
	docker exec -it $(PHP_CONTAINER) bin/console doctrine:migrations:migrate -n

app.run_fixtures:
	docker exec -it $(PHP_CONTAINER) bin/console doctrine:fixtures:load -n

app.run_fixtures:
	docker exec -it $(PHP_CONTAINER) bin/console doctrine:fixtures:load -n

app.tests:
	make app.run_fixtures
	docker exec -it $(PHP_CONTAINER) bin/phpunit
