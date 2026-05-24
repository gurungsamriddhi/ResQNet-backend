#!/bin/bash
export PHP_INI_SCAN_DIR="/etc/php/8.2/cli/conf.d:/home/asmita/.php-ext"
exec php artisan serve "$@"
