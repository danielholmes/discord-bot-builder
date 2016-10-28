<?php


namespace DiscordBotBuilder;


use DiscordBotBuilder\Command\Command;
use DiscordBotBuilder\MessageMatcher\MessageMatcher;

class CommandCollection
{
  /** @var array[] */
  private $bindings;
  
  public function __construct() {
    $this->bindings = [];
  }

  /**
   * @param MessageMatcher $binding
   * @param Command $command
   */
  public function add(MessageMatcher $binding, Command $command) {
    $this->bindings[] = [$binding, $command];
  }

  /**
   * @return MessageMatcher[]
   */
  public function getMatchers() {
    return array_map(function(array $pair) { return $pair[0]; }, $this->bindings);
  }

  /**
   * @param MessageMatcher $binding
   * @return Command|null
   */
  public function offsetGet(MessageMatcher $binding) {
    foreach ($this->bindings as $pair) {
      if ($pair[0] === $binding) {
        return $pair[1];
      }
    }
    
    return null;
  }
}