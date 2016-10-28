<?php


namespace DiscordBotBuilder\MessageMatcher;


use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class EmptyMessageMatcher implements MessageMatcher
{
  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    return empty(trim(MessageHelper::stripMentions($message)));
  }
}