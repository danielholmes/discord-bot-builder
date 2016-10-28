<?php

namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class CatchAllMessageMatcher implements MessageMatcher
{
  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    return true;
  }
}