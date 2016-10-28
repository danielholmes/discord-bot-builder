<?php

namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

interface MessageMatcher
{
  /**
   * @param Message $message
   * @return boolean
   */
  public function matches(Message $message, DiscordBot $bot);
}