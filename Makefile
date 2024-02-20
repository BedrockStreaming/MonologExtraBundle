SHELL=bash -o pipefail
SOURCE_DIR = $(shell pwd)

.PHONY: all
all: install test

.PHONY: install
install:
	composer install

.PHONY: test
test:
	./vendor/bin/atoum