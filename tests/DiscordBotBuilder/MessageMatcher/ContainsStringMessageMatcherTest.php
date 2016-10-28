<?php

namespace DiscordBotBuilder\MessageMatcher;

use DiscordBotBuilder\DiscordBot;
use DiscordBotBuilder\TestHelp\StubMessage;
use DiscordBotBuilder\TestHelp\StubUser;
use PHPUnit\Framework\TestCase;

class ContainsStringMessageMatcherTest extends TestCase
{
  /** @var ContainsStringMessageMatcher */
  private $binding;

  /** @var ContainsStringMessageMatcher */
  private $spaceBinding;
  
  /** @var DiscordBot */
  private $bot;
  
  public function setUp() {
    $this->bot = \Phake::mock('DiscordBotBuilder\DiscordBot');
    $this->binding = new ContainsStringMessageMatcher('hello');
    $this->spaceBinding = new ContainsStringMessageMatcher('hello world');
  }

  public function testExactWorks() {
    $message = new StubMessage(['content' => 'hello']);
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testSpaceExactWorks() {
    $message = new StubMessage(['content' => 'hello world']);
    self::assertThat($this->spaceBinding->matches($message, $this->bot), self::isTrue());
  }

  public function testNotExactDoesntWork() {
    $message = new StubMessage(['content' => 'johnno']);
    self::assertThat($this->binding->matches($message, $this->bot), self::isFalse());
  }

  public function testContainsWorks() {
    $message = new StubMessage(['content' => 'hello world']);
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testContainsInMiddleOfWordDoesntWork() {
    $message = new StubMessage(['content' => 'hellobalooza']);
    self::assertThat($this->binding->matches($message, $this->bot), self::isFalse());
  }

  public function testContainsWithWhiteSpaceWorks() {
    $message = new StubMessage(['content' => "  hello \t \n"]);
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testContainsWithMentionWorks() {
    $message = new StubMessage(
      [
        'content' => "<@123>hello",
        'mentions' => [new StubUser(['id' => '123'])]
      ]
    );
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }
}