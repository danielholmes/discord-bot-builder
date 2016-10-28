<?php

namespace DiscordBotBuilder\MessageMatcher;

interface ContentMessageMatcher extends MessageMatcher
{
  /**
   * @return string
   */
  public function getLabel();
}