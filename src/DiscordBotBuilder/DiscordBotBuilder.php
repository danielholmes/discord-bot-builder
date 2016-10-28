<?php


namespace DiscordBotBuilder;


use Discord\Discord;
use DiscordBotBuilder\Command\Command;
use DiscordBotBuilder\MessageMatcher\CatchAllMessageMatcher;
use DiscordBotBuilder\MessageMatcher\MessageMatcher;
use DiscordBotBuilder\MessageMatcher\ContainsStringMessageMatcher;
use DiscordBotBuilder\MessageMatcher\EmptyMessageMatcher;
use DiscordBotBuilder\MessageMatcher\StartsWithMessageMatcher;

class DiscordBotBuilder
{
  /** @var string */
  private $botToken;
  
  /** @var int */
  private $botUserId;
  
  /** @var CommandCollection */
  private $commandBindings;
  
  /**
   * @param string $botToken
   * @param int $botUserId
   */
  public function __construct($botToken, $botUserId) {
    $this->botToken = $botToken;
    $this->botUserId = $botUserId;
    $this->commandBindings = new CommandCollection();
  }

  /**
   * @param string $component
   * @param Command $command
   * @return $this
   */
  public function addContainsStringCommand($component, Command $command) {
    return $this->addCommand(new ContainsStringMessageMatcher($component), $command);
  }

  /**
   * @param Command $command
   * @return $this
   */
  public function addEmptyMessageCommand(Command $command) {
    return $this->addCommand(new EmptyMessageMatcher(), $command);
  }

  /**
   * @param string $component
   * @param Command $command
   * @return $this
   */
  public function addSimpleCommand($component, Command $command) {
    return $this->addCommand(new StartsWithMessageMatcher($component), $command);
  }

  /**
   * @param Command $command
   * @return $this
   */
  public function addCatchAllCommand(Command $command) {
    return $this->addCommand(new CatchAllMessageMatcher(), $command);
  }

  /**
   * @param MessageMatcher $binding
   * @param Command $command
   * @return $this
   */
  public function addCommand(MessageMatcher $binding, Command $command) {
    $this->commandBindings->add($binding, $command);
    return $this;
  }

  /**
   * @return DiscordBot
   */
  public function build() {
    $discord = new Discord(['token' => $this->botToken]);
    return new DiscordBot($discord, $this->botUserId, $this->commandBindings);
  }
}