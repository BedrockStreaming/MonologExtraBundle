.PHONY: help rector rector-dry rector-fix cs cs-fix phpstan

help:
	@echo "Available commands:"
	@echo "  make rector-fix      - Run Rector and apply changes"
	@echo "  make rector-dry      - Run Rector in dry-run mode (no modifications)"
	@echo "  make cs              - Run PHP CS Fixer in check mode (no modifications)"
	@echo "  make cs-fix          - Run PHP CS Fixer and apply changes"
	@echo "  make phpstan         - Run PHPStan static analysis"


rector: rector-dry

rector-dry:
	@echo "Running Rector in dry-run mode..."
	@./vendor/bin/rector process --dry-run

rector-fix:
	@echo "Running Rector and applying changes..."
	@./vendor/bin/rector process

cs:
	@echo "Running PHP CS Fixer in check mode..."
	@./vendor/bin/php-cs-fixer check --diff

cs-fix:
	@echo "Running PHP CS Fixer and applying changes..."
	@./vendor/bin/php-cs-fixer fix

phpstan:
	@echo "Running PHPStan..."
	@./vendor/bin/phpstan analyse -c phpstan.dist.neon
