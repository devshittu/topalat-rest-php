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
> tangsail artisan migrate:refresh --seed && tangsail artisan passport:install

All in one line

> tangsail  artisan clear-compiled && tangsail  artisan optimize:clear && tangsail composer dump-autoload 


