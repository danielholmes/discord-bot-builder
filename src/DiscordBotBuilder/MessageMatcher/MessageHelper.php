<?php


namespace DiscordBotBuilder\MessageMatcher;


use Discord\Parts\Channel\Message;

class MessageHelper
{
  /**
   * @param Message $message
   * @return string
   */
  public static function stripMentions(Message $message) {
    $content = $message->content;
    foreach ($message->mentions as $mention) {
      $mentionString = '<@' . $mention->id . '>';
      $content = trim(str_replace($mentionString, '', $content));
    }
    return $content;
  }
}