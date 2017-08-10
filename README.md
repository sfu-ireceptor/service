# iReceptor Service

## Server requirements
https://laravel.com/docs/5.4/installation#server-requirements


## Installation
```
# get source code
git clone https://github.com/sfu-ireceptor/service.git ireceptor_service
cd ireceptor_service

# make some folders writable
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# install PHP dependencies
composer install

# add config file
cp .env.example .env

# generate application key (config file will be updated)
php artisan key:generate

# add base url and database info in config file
vi .env
```

