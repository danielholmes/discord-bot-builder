<?php

if (count($argv) !== 2)
{
    throw new RuntimeException('Usage: php debug-info.php [discord_token]');
}

$token = $argv[1];

require_once(__DIR__ . '/../vendor/autoload.php');

use Discord\Discord;

$discord = new Discord(['token' => $token]);
$discord->on('ready', function(Discord $discord) {
  echo 'Ready' . "\n";
  echo count($discord->guilds) . ' Guilds' . "\n";
  foreach ($discord->guilds as $guild) {
    /** @var \Discord\Parts\Guild\Guild $guild */
    echo '# ' . $guild->name . "\n";
    echo '## Channels:' . "\n";
    foreach ($guild->channels as $channel) {
      /** @var \Discord\Parts\Channel\Channel $channel */
      echo ' - ' . $channel->name . ' (' . $channel->id . ') type: ' . $channel->type . "\n";
    }
    echo '## Roles:' . "\n";
    foreach ($guild->roles as $role) {
      /** @var \Discord\Parts\Guild\Role $role */
      echo ' - ' . $role->name . ' (' . $role->id . ') color: ' . $role->color . "\n";
    }
    echo '## First Member:' . "\n";
    foreach ($guild->members as $member) {
      /** @var \Discord\Parts\User\Member $member */
      echo ' - ' . json_encode($member->getRawAttributes(), JSON_PRETTY_PRINT) . "\n";
      break;
    }
  }
  echo 'Done' . "\n";
  exit();
});
$discord->run();