<?php


namespace DiscordBotBuilder\MessageMatcher;


use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class NotFromMessageMatcher implements MessageMatcher
{
  /** @var int */
  private $userId;

  /**
   * @param int $userId
   */
  public function __construct($userId) {
    $this->userId = $userId;
  }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    return $message->author->id != $this->userId;
  }
}