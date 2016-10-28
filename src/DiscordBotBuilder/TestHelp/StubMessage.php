<?php

namespace DiscordBotBuilder\TestHelp;

use Discord\Parts\Channel\Message;

class StubMessage extends Message {
  public function __construct(array $props = []) {
    $props = array_merge(
      [
        'content' => '',
        'mentions' => [],
        'author' => new StubUser(),
        'channel' => \Phake::mock('Discord\Parts\Channel\Channel')
      ],
      $props
    );
    $this->attributes = $props;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getAttribute($key)
  {
    return @$this->attributes[$key];
  }
}