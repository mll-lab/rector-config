.PHONY: it
it: fix normalize stan ## Perform quality checks

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep --extended-regexp '^[a-zA-Z0-9_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: setup
setup: vendor ## Set up the project

.PHONY: fix
fix: ## Fix PHP code
	vendor/bin/rector process
	vendor/bin/php-cs-fixer fix

.PHONY: normalize
normalize: ## Normalize composer.json
	composer normalize

.PHONY: stan
stan: vendor ## Runs a static analysis with phpstan
	vendor/bin/phpstan analyse --configuration=phpstan.neon

vendor: composer.json ## Install dependencies through composer
	composer update
