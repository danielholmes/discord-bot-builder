<?php

namespace DiscordBotBuilder\TestHelp;

use Discord\Parts\User\User;

class StubUser extends User {
  public function __construct(array $props = []) {
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