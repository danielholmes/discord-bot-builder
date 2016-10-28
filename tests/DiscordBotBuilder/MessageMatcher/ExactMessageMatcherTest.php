<?php

namespace DiscordBotBuilder\MessageMatcher;

use Discord\Discord;
use DiscordBotBuilder\DiscordBot;
use DiscordBotBuilder\TestHelp\StubMessage;
use DiscordBotBuilder\TestHelp\StubUser;
use PHPUnit\Framework\TestCase;

class ExactMessageMatcherTest extends TestCase
{
  /** @var Discord */
  private $discord;

  /** @var ExactMessageMatcher */
  private $binding;

  /** @var ExactMessageMatcher */
  private $spaceBinding;

  /** @var DiscordBot*/
  private $bot;
  
  public function setUp() {
    $this->bot = \Phake::mock('DiscordBotBuilder\DiscordBot');
    $this->discord = \Phake::mock('Discord\Discord');
    $this->binding = new ExactMessageMatcher('hello');
    $this->spaceBinding = new ExactMessageMatcher('hello world');
  }

  public function testExactWorks() {
    $message = new StubMessage(['content' => 'hello']);
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testExactDifferentCaseWorks() {
    $message = new StubMessage(['content' => 'HeLLo', 'mentions' => []]);
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testExactWithSpacesWorks() {
    $message = new StubMessage(['content' => 'hello world', 'mentions' => []]);
    self::assertThat($this->spaceBinding->matches($message, $this->bot), self::isTrue());
  }

  public function testNotExactDoesntWork() {
    $message = new StubMessage(['content' => 'johnno', 'mentions' => []]);
    self::assertThat($this->binding->matches($message, $this->bot), self::isFalse());
  }

  public function testContainsDoesntWork() {
    $message = new StubMessage(['content' => 'hello world', 'mentions' => []]);
    self::assertThat($this->binding->matches($message, $this->bot), self::isFalse());
  }

  public function testContainsWithWhiteSpaceWorks() {
    $message = new StubMessage(['content' => "  hello \t \n", 'mentions' => []]);
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testContainsWithMentionWorks() {
    $message = new StubMessage(
      [
        'content' => "<@123>  hello",
        'mentions' => [new StubUser(['id' => '123'])]
      ]
    );
    self::assertThat($this->binding->matches($message, $this->bot), self::isTrue());
  }

  public function testSpaceContainsWithMentionWorks() {
    $message = new StubMessage(
      [
        'content' => '<@123>  hello world',
        'mentions' => [new StubUser(['id' => '123'])]
      ]
    );
    self::assertThat($this->spaceBinding->matches($message, $this->bot), self::isTrue());
  }
}