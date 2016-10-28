<?php


namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class ThrowExceptionCommand implements Command
{
  /**
   * @inheritdoc
   */
  public function execute(DiscordBot $bot, Message $message) {
    throw new \RuntimeException('Testing exception handling');
  }

  /**
   * @inheritdoc
   */
  public function getDescription() {
    return 'Throw an exception to test error handling/notifications';
  }
}