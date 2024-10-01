# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php-ansible

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer

# Misc
.DEFAULT_GOAL = help

## —— php-ansible Makefile 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the php-fpm container
	@$(PHP_CONT) sh

bash: ## Connect to the php-fpm container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(PHP_CONT) vendor/bin/phpunit -c phpunit.xml.dist $(c)

analyze: ## Start analysis with phpstan, pass the parameter "c=" to add options to phpstan. Default config ist always used, example: make analyze c="--group e2e"
	@$(eval c ?=)
	@$(PHP_CONT) vendor/bin/phpstan --configuration=phpstan.neon $(c)

codestyle: ## Start codestyle analysis with phpcs, pass the parameter "c=" to add options to phpcs. Default config ist always used. Example: make codestyle c="--parallel=2"
	@$(eval c ?=)
	@$(PHP_CONT) vendor/bin/phpcs --standard=phpcs.xml.dist $(c)

codestyle-fix: ## Start codestyle analysis with phpcbf, pass the parameter "c=" to add options to phpcbf. Default config ist always used. Example: make codestyle c="--parallel=2"
	@$(eval c ?=)
	@$(PHP_CONT) vendor/bin/phpcbf --standard=phpcs.xml.dist $(c)

psalm: ## Start code analysis with psalm, pass the parameter "c=" to add options to psalm. Default config ist always used. Example: make codestyle c="--level=2"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=dev php vendor/bin/psalm --config=psalm.xml $(c)

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req phpstan/phpstan'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer