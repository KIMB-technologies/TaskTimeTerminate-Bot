# TaskTimeTerminate Telegram Bot

> A Telegram Bot providing a TaskTimeTerminate-Client.  
> See https://github.com/KIMB-technologies/TaskTimeTerminate for information about TTT

## Installation
1. Copy the [docker-compose.yml](https://github.com/KIMB-technologies/TaskTimeTerminateBot/blob/master/docker-compose.yml)
2. Make sure to bind to a free port
3. Change the volume to a location wich is regularly made a backup from. (Inside the container all data is stored in `/code/data`)
4. Edit environment variables
	- `DEVMODE` Should always be `false` (enables error messages)
      - `WEBHOOK_URL` The URL where Telegram sends Webhook requests (needs https); URL to the webroot of this container
      - `WEBHOOK_UNREGISTER` Bot will register webhook if `false` and unregister if `true` (check always done on container startup) 
      - `REGISTER_TOKEN` To use the bot a user will need a token and run `/start <token>`; specify the token here
      - `TELEGRAM_API_KEY` The Telegram API Key 
      - `TELEGRAM_BOT_NAME` The Username of the Telegram Bot (without `@`)
3. NGINX Reverse Proxy for Hook
	- Example for Nginx reverse proxy
	```nginx
		location /telegrambot/ITYFNobd7CfNRd8ojFzhrNhFJ2dSEfai41mwc3KqzXlOTgia6M/ {
			proxy_pass http://127.0.0.1:8080/;
			proxy_http_version 1.1;
			proxy_read_timeout 3m;
			proxy_send_timeout 3m;
		}
	```
5. Start the bot in Telegram and use like local TTT-Client.