<?php


namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\Command\Command;
use DiscordBotBuilder\DiscordBot;

class ContainsStringMessageMatcher implements ContentMessageMatcher
{
  /** @var string */
  private $component;

  /**
   * @param string $component
   */
  public function __construct($component) {
    $this->component = $component;
  }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    return (boolean) preg_match('/\b' . $this->component . '\b/', MessageHelper::stripMentions($message));
  }

  /**
   * @inheritdoc
   */
  public function getLabel() {
    return $this->component;
  }
}