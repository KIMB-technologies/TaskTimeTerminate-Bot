#!/bin/sh 

# Un-/ Register webhook
php /code/bot/setHook.php

# file rights
chown -R www-data:www-data /code/data/

# start cronjob
su-exec www-data php /code/bot/cron.php &