> alias tangsail ./vendor/bin/sail \
> tangsail up \

Please perform a clean with:
> tangsail  artisan clear-compiled \
> tangsail  artisan optimize:clear \
> tangsail composer dump-autoload \
> tangsail  artisan optimize \

Run your migrations as:

> tangsail artisan migrate:refresh --seed \
> tangsail artisan migrate:reset \
> tangsail artisan passport:install \
> tangsail artisan config:cache
> 
>  tangsail artisan config:cache && tangsail artisan route:list && tangsail artisan route:cache && tangsail artisan migrate:refresh --seed && tangsail artisan passport:install &&  tangsail artisan passport:client --password --provider clients

All in one line

> tangsail  artisan clear-compiled && tangsail  artisan optimize:clear && tangsail composer dump-autoload 


#Production

> php  artisan clear-compiled && php  artisan optimize:clear && php composer dump-autoload && php artisan migrate:refresh --seed && php artisan passport:install && php artisan config:cache

Issue:
There is no existing directory at “…/storage/logs” and its not buildable: Permission denied
Solution:
php artisan route:clear
php artisan config:clear
php artisan cache:clear


php  artisan route:list
composer dump-autoload
php artisan route:cache

