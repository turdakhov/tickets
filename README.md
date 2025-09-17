# Installation


### Base installation

- Configure .env file. env. example file presents.
- run:

`$ composer install`
`$ php artisan key:generate`
`$ npm i`

`$ php artisan migrate` or
`$ php artisan migrate --seed` if you want to have some example categories and test user (email: test@example.com, password: password)

`$ php artisan wayfinder:generate`
`$ npm run build`

### Telegram Bot configuration
Run:
`$ php artisan telegraph:new-bot`
Enter the bot token and other options;

To configure Webhook run:
`$ php artisan telegraph:set-webhook {bot_id}`
Bot id is required if you have more than one bot.

### Slack Integration

For slack integration fill the .env SLACK_BOT_USER_OAUTH_TOKEN and SLACK_BOT_USER_DEFAULT_CHANNEL values.
