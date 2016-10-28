<?php


namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use DiscordBotBuilder\DiscordBot;
use DiscordBotBuilder\MessageMatcher\PrivateMessageMatcher;

class RepeatCommand implements Command
{
  /**
   * @inheritdoc
   */
  public function execute(DiscordBot $bot, Message $message) {
    $parts = explode(' ', trim(explode('repeat', $message->content)[1]));
    $channelName = $parts[0];

    $channel = null;
    if (PrivateMessageMatcher::instance()->matches($message, $bot)) {
      foreach ($bot->getAllGuildsWithUser($message->author) as $guild) {
        $channel = $this->getChannelByName($guild, $channelName);
        if ($channel !== null) {
          break;
        }
      }
    } else {
      $channel = $this->getChannelByName($message->channel->guild, $channelName);
    }

    if ($channel !== null) {
      $say = array_slice($parts, 1);
      $channel->sendMessage(join(' ', $say));
      $message->reply('Message sent to #' . $channelName);
      return;
    }

    $message->reply($channelName . ' channel not found');
  }

  /**
   * @param Guild $guild
   * @param string $channelName
   * @return Channel
   */
  private function getChannelByName(Guild $guild, $channelName) {
    foreach ($guild->channels as $channel) {
      if ($channel->name === $channelName) {
        return $channel;
      }
    }

    return null;
  }

  /**
   * @inheritdoc
   */
  public function getDescription() {
    return 'Repeat the given phrase in the given channel';
  }
}