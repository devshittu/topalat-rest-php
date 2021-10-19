Backend flow check list

cd /var/www/api.topalat.ng/
git clone git@github.com:devshittu/topalat-rest-php.git

sudo rm -rf html

mv topalat-rest-php html && cd html

save this in nano .env file
nano .env

> 
APP_NAME='Topalat NG'
APP_ENV=production
APP_KEY=base64:KbUst7x24wP2vIsPPsd5wj7bH5cbSk+c5B9zNB618dI=
APP_DEBUG=false
APP_URL=http://api.topalat.ng

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=topalatdb
DB_USERNAME=topalatdbuser
DB_PASSWORD=12345678

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=memcached

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mail.privateemail.com
MAIL_PORT=465
MAIL_USERNAME=admin@topalat.ng
MAIL_PASSWORD=12345678Aa
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=admin@topalat.ng
MAIL_FROM_NAME="${APP_NAME}"
MAIL_CONTACT_ADDRESS=contact@topalat.ng


install composer

composer install


Please perform a clean with:
> php artisan key:generate \
> php  artisan clear-compiled \
> php  artisan optimize \
> sudo php artisan cache:clear \
> > php artisan migrate:refresh --seed && php artisan passport:install \



> sudo chown -R www-data:www-data /var/www/api.topalat.ng/html/storage/ \
> sudo chown -R www-data:www-data /var/www/api.topalat.ng/html/bootstrap/cache/
> sudo systemctl reload nginx.service 


