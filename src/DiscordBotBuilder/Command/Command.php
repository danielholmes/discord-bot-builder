<?php

namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

interface Command
{
  /**
   * @param DiscordBot $bot
   * @param Message $message
   */
  public function execute(DiscordBot $bot, Message $message);

  /**
   * @return string
   */
  public function getDescription();
}