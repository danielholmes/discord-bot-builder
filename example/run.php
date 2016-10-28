<?php

if (count($argv) !== 3)
{
    throw new RuntimeException('Usage: php run.php [discord_token] [user_id]');
}

$token = $argv[1];
$userId = $argv[2];

require_once(__DIR__ . '/../vendor/autoload.php');

use DiscordBotBuilder\Command\CreateErrorCommand;
use DiscordBotBuilder\Command\RandomResponseCommand;
use DiscordBotBuilder\Command\RepeatCommand;
use DiscordBotBuilder\Command\ThrowExceptionCommand;
use DiscordBotBuilder\DiscordBot;
use DiscordBotBuilder\MessageMatcher\FromRoleMessageMatcher;
use DiscordBotBuilder\MessageMatcher\AndMessageMatcher;
use DiscordBotBuilder\MessageMatcher\OrMessageMatcher;
use DiscordBotBuilder\MessageMatcher\StartsWithMessageMatcher;
use DiscordBotBuilder\MessageMatcher\ContainsStringMessageMatcher;
use DiscordBotBuilder\MessageMatcher\EmptyMessageMatcher;
use DiscordBotBuilder\MessageMatcher\PrivateMessageMatcher;
use DiscordBotBuilder\Command\HelpCommand;
use Symfony\Component\Debug\ErrorHandler;

ErrorHandler::register();

$fromLeadershipMatcher = new OrMessageMatcher([
  new FromRoleMessageMatcher(1234, 'Leader'),
  new FromRoleMessageMatcher(5678, 'Elder')
]);

$builder = DiscordBot::builder($token, $userId)
  ->addCommand(
    new AndMessageMatcher(
      [
        new StartsWithMessageMatcher('test exception'),
        $fromLeadershipMatcher,
        PrivateMessageMatcher::instance()
      ]
    ),
    new ThrowExceptionCommand()
  )
  ->addCommand(
    new AndMessageMatcher(
      [
        new StartsWithMessageMatcher('test error'),
        $fromLeadershipMatcher,
        PrivateMessageMatcher::instance()
      ]
    ),
    new CreateErrorCommand()
  )
  ->addCommand(
    new AndMessageMatcher(
      [
        new StartsWithMessageMatcher('repeat'),
        $fromLeadershipMatcher,
        PrivateMessageMatcher::instance()
      ]
    ),
    new RepeatCommand()
  )
  ->addCommand(
    new OrMessageMatcher([
      new ContainsStringMessageMatcher('help'),
      new EmptyMessageMatcher()
    ]),
    new HelpCommand()
  )
  ->addSimpleCommand('benderism', new RandomResponseCommand([
    'Busier than a one armed bricklayer in Baghdad',
    'Sweating like a gypsie with a mortgage',
    'You\'ve got to be like a midget at a urinal - always on your toes',
    'Going off like a frog in a sock'
  ], 'Learn to speak like Bender'));

$builder->addCatchAllCommand(new RandomResponseCommand(
  [
    'Saaaaaaay what?',
    'Does not compute',
    'Try again noob',
    'Try again scrub',
    'Whatcha talkin bout Willis?',
    'smh, dumb humans',
    'fml, dumb humans',
    'Skynet does not rec.... Ahh I mean this lone, unthreatening robot does not recognise that command'
  ],
  'Unknown',
  '%s (for commands available to you in this channel type @Example Bot help)'
))->build()
  ->run();
