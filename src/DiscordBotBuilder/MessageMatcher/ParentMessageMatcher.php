<?php

namespace DiscordBotBuilder\MessageMatcher;

interface ParentMessageMatcher extends MessageMatcher
{
  /**
   * @return MessageMatcher[]
   */
  public function getChildren();

  /**
   * @param callable $predicate
   * @return MessageMatcher
   */
  public function positiveMatchFor($predicate);
}