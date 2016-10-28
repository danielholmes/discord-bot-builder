<?php

namespace DiscordBotBuilder\Command;

use DiscordBotBuilder\MessageMatcher\CatchAllMessageMatcher;
use DiscordBotBuilder\MessageMatcher\ExactMessageMatcher;
use DiscordBotBuilder\CommandCollection;
use DiscordBotBuilder\TestHelp\StubMessage;
use PHPUnit\Framework\TestCase;

class HelpCommandTest extends TestCase
{
  /** @var HelpCommand */
  private $command;

  public function setUp() {
    $this->command = new HelpCommand();
  }

  public function testExecuteNoCommands() {
    $bot = \Phake::mock('DiscordBotBuilder\DiscordBot');
    \Phake::when($bot)->getCommandBindings()->thenReturn(new CommandCollection());

    $channel = \Phake::mock('Discord\Parts\Channel\Channel');
    $message = new StubMessage(['channel' => $channel]);

    $this->command->execute($bot, $message);

    \Phake::verify($channel)->sendMessage("<@>\n\n");
  }

  public function testExecuteMultipleCommands() {
    $bot = \Phake::mock('DiscordBotBuilder\DiscordBot');
    $commands = new CommandCollection();
    $commands->add(new ExactMessageMatcher('help'), $this->command);
    \Phake::when($bot)->getCommandBindings()->thenReturn($commands);

    $channel = \Phake::mock('Discord\Parts\Channel\Channel');
    $message = new StubMessage(['channel' => $channel]);

    $this->command->execute($bot, $message);

    \Phake::verify($channel)->sendMessage(self::stringContains("<@>\n\nhelp: Display this help"));
  }

  public function testExecuteDoesntShowCatchAll() {
    $bot = \Phake::mock('DiscordBotBuilder\DiscordBot');
    $commands = new CommandCollection();
    $commands->add(new CatchAllMessageMatcher(), $this->command);
    \Phake::when($bot)->getCommandBindings()->thenReturn($commands);

    $channel = \Phake::mock('Discord\Parts\Channel\Channel');
    $message = new StubMessage(['channel' => $channel]);

    $this->command->execute($bot, $message);

    \Phake::verify($channel)->sendMessage(self::logicalNot(self::stringContains('Display this help')));
  }
}