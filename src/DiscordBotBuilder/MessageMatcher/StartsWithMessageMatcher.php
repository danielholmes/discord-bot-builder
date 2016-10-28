<?php


namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class StartsWithMessageMatcher implements ContentMessageMatcher
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
    return stripos(trim(MessageHelper::stripMentions($message)), $this->component) === 0;
  }

  /**
   * @inheritdoc
   */
  public function getLabel() {
    return $this->component;
  }
}