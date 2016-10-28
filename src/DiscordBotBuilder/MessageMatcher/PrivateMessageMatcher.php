<?php

namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class PrivateMessageMatcher implements MessageMatcher
{
  private function __construct() { }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    return $message->channel->is_private;
  }

  /** @var PrivateMessageMatcher */
  private static $instance;

  /**
   * @return PrivateMessageMatcher
   */
  public static function instance() {
    if (self::$instance === null) {
      self::$instance = new PrivateMessageMatcher();
    }

    return self::$instance;
  }
}