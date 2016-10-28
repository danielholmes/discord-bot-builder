<?php

namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class ExactMessageMatcher implements ContentMessageMatcher
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
    return strtolower(trim(MessageHelper::stripMentions($message))) === strtolower($this->component);
  }

  /**
   * @inheritdoc
   */
  public function getLabel() {
    return $this->component;
  }
}