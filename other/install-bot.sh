cd /code/bot/

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php ./composer-setup.php
rm ./composer-setup.php

php ./composer.phar install
rm ./composer.phar

mv ./vendor ../