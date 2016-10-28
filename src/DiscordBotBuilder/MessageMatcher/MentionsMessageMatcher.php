<?php


namespace DiscordBotBuilder\MessageMatcher;


use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class MentionsMessageMatcher implements MessageMatcher
{
  /** @var int */
  private $userId;

  /** @var string */
  private $userName;

  /**
   * @param int $userId
   * @param string $userName
   */
  public function __construct($userId, $userName) {
    $this->userId = $userId;
    $this->userName = $userName;
  }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    foreach ($message->mentions as $mention) {
      if ($mention->id == $this->userId) {
        return true;
      }
    }

    return false;
  }
}