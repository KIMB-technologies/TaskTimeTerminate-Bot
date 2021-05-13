FROM kimbtechnologies/php_nginx:8-latest 

# copy into webroot
COPY ./other/hook.php  /php-code/hook.php
COPY ./other/index.php  /php-code/index.php

# startup file 
COPY ./other/*.sh  /

# copy bot
COPY ./bot/  /code/bot/

# prepare container, download ttt, install composer
RUN  mkdir /code/ttt/ && mkdir /code/data/ \ 
	&& apk add --update --no-cache git su-exec \
	&& sh /install-ttt.sh && rm /install-ttt.sh \
	&& sh /install-bot.sh && rm /install-bot.sh 