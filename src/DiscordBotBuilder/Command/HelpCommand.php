<?php

namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\MessageMatcher\AndMessageMatcher;
use DiscordBotBuilder\MessageMatcher\ContentMessageMatcher;
use DiscordBotBuilder\MessageMatcher\MessageMatcher;
use DiscordBotBuilder\DiscordBot;
use DiscordBotBuilder\MessageMatcher\ParentMessageMatcher;

class HelpCommand implements Command
{
  /**
   * @inheritdoc
   */
  public function execute(DiscordBot $bot, Message $message) {
    $content = $this->buildContent($bot, $message);
    $message->channel->sendMessage("{$message->author}\n\n{$content}");
  }

  /**
   * @param DiscordBot $bot
   * @param Message $message
   * @return string
   */
  private function buildContent(DiscordBot $bot, Message $message) {
    $bindings = $bot->getCommandBindings();

    $permittedMatchers = array_values(
      array_filter(
        $bindings->getMatchers(),
        function(MessageMatcher $matcher) use ($bindings, $message, $bot) {
          return $this->hasContentMatcher($matcher) &&
            $this->matcherPermittedInMessageContext($matcher, $message, $bot);
        }
      )
    );

    $lines = array_map(
      function(MessageMatcher $matcher) use ($bindings) {
        $contentMatcher = $this->getContentMatcher($matcher);
        return $contentMatcher->getLabel() . ': ' . $bindings->offsetGet($matcher)->getDescription();
      },
      $permittedMatchers
    );
    usort($lines, 'strcmp');
    return join("\n", $lines);
  }

  /**
   * @param MessageMatcher $matcher
   * @return boolean
   */
  private function hasContentMatcher(MessageMatcher $matcher) {
    return $this->getContentMatcher($matcher) !== null;
  }

  /**
   * @param MessageMatcher $matcher
   * @return ContentMessageMatcher|null
   */
  private function getContentMatcher(MessageMatcher $matcher) {
    if ($matcher instanceof ParentMessageMatcher) {
      $childMatchers = $matcher->getChildren();
      foreach ($childMatchers as $childMatcher) {
        $contentMatcher = $this->getContentMatcher($childMatcher);
        if ($contentMatcher !== null) {
          return $contentMatcher;
        }
      }

      return null;
    }

    if ($matcher instanceof ContentMessageMatcher) {
      return $matcher;
    }

    return null;
  }

  /**
   * @param MessageMatcher $matcher
   * @param Message $message
   * @param DiscordBot $bot
   * @return boolean
   */
  private function matcherPermittedInMessageContext(MessageMatcher $matcher, Message $message, DiscordBot $bot) {
    $parent = new AndMessageMatcher([$matcher]);
    $positiveContent = $parent->positiveMatchFor(function(MessageMatcher $matcher) {
      return $matcher instanceof ContentMessageMatcher;
    });
    return $positiveContent->matches($message, $bot);
  }

  /**
   * @return string
   */
  public function getDescription() {
    return 'Display this help';
  }
}