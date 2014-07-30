rm -rf ./app/cache/prod/*
rm -rf ./app/cache/dev/*
php app/console assetic:dump
php app/console assets:install --symlink web
composer install
