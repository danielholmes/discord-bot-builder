<?php


namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class OrMessageMatcher implements ParentMessageMatcher
{
  /** @var MessageMatcher[] */
  private $matchers;

  /**
   * @param MessageMatcher[] $matchers
   */
  public function __construct(array $matchers) {
    $this->matchers = $matchers;
  }

  /**
   * @inheritdoc
   */
  public function getChildren() {
    return $this->matchers;
  }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    foreach ($this->matchers as $matcher) {
      if ($matcher->matches($message, $bot)) {
        return true;
      }
    }
    
    return false;
  }

  /**
   * @inheritdoc
   */
  public function positiveMatchFor($predicate) {
    return new OrMessageMatcher(
      array_map(
        function(MessageMatcher $matcher) use($predicate) {
          if (call_user_func_array($predicate, [$matcher])) {
            return new CatchAllMessageMatcher();
          }

          if ($matcher instanceof ParentMessageMatcher) {
            return $matcher->positiveMatchFor($predicate);
          }

          return $matcher;
        },
        $this->matchers
      )
    );
  }
}