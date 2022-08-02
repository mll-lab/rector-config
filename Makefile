.PHONY: it
it: fix normalize ## Perform quality checks

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: setup
setup: vendor ## Set up the project

.PHONY: fix
fix: ## Fix PHP code
	vendor/bin/rector process
	vendor/bin/php-cs-fixer fix

.PHONY: normalize
normalize: ## Normalize composer.json
	composer normalize

vendor: composer.json ## Install dependencies through composer
	composer install
