<?php

namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class CreateErrorCommand implements Command
{
  /**
   * @inheritdoc
   */
  public function execute(DiscordBot $bot, Message $message) {
    $doesntExist->hello();
  }

  /**
   * @inheritdoc
   */
  public function getDescription() {
    return 'Throw an error to test error handling/notifications';
  }
}