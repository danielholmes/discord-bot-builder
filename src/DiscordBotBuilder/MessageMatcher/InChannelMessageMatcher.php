<?php


namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class InChannelMessageMatcher implements MessageMatcher
{
  /** @var int */
  private $id;

  /** @var string */
  private $name;

  /**
   * @param int $id
   * @param string $name
   */
  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    return $message->channel_id == $this->id;
  }
}