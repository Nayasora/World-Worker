PHP      = ../PHP-Binaries/bin/php7/bin/php
COMPOSER = /usr/bin/composer
BIN      = ./vendor/bin/

analyze:
	$(PHP) $(BIN)phpstan analyze src --level 4

fix:
	$(PHP) $(BIN)php-cs-fixer fix ./

install:
	$(PHP) $(COMPOSER) install

update:
	$(PHP) $(COMPOSER) update