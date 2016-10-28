# Discord Bot Builder

A framework to help build a [Discord](https://discordapp.com/) bot and route incoming messages. Features include:

 - Restrict commands to particular channels
 - Restrict commands to particular roles
 - Respond to various message properties only including:
   - Private messages
   - Mentions
   - Messages containing particular phrases
   - Messages from certain people

It's a work in progress, in particular it could do with a good DSL in front of the matchers in order make specifying a
bot much prettier on the eyes.


## Development

```
php composer.phar install
```


## Running

See `examples`

 1. Create your bot on the [Discord Developer Site]() 
 2. Copy your bot client/application id
 3. Go to 
    [https://discordapp.com/oauth2/authorize?client_id=CLIENT_ID_GOES_HERE&scope=bot&permissions=0](https://discordapp.com/oauth2/authorize?client_id=CLIENT_ID_GOES_HERE&scope=bot&permissions=0) 
    You can set permissions for the bot here. Permissions can be found on the developer pages under permission.
 4. Select server and click authorize.
 5. `php examples/run.php [your_bot_token] [bot_client_id]`
 

Included in the example is an upstart script. To run on a Ubuntu server:

```
cp examplebot.conf /etc/init
# Change paths in config to match your installation
vi /etc/init/examplebot.conf
sudo service examplebot restart
```


## Running Tests

 - All: `vendor/bin/phpunit`
 - Individual: `vendor/bin/phpunit tests/DiscordBotBuilder/Command/HelpCommandTest.php`


## Utils

See info about the bot's current servers:

```
php utils/debug-info.php [discord_token]
```