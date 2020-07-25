A PHP script that sends a message to a Slack channel
### Install
`composer install`
### Run
`php -r "require './src/slack.php'; messageToChannel('TOKEN', 'CHANNEL', 'MESSAGE');"`
### Test
Replace token in test files

`phpunit tests`