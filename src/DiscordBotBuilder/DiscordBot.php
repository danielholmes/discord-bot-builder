<?php

namespace DiscordBotBuilder;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Parts\User\User;
use DiscordBotBuilder\MessageMatcher\AndMessageMatcher;
use DiscordBotBuilder\MessageMatcher\MessageMatcher;
use DiscordBotBuilder\MessageMatcher\MentionsMessageMatcher;
use DiscordBotBuilder\MessageMatcher\NotFromMessageMatcher;
use DiscordBotBuilder\MessageMatcher\OrMessageMatcher;
use DiscordBotBuilder\MessageMatcher\PrivateMessageMatcher;

class DiscordBot
{
  /** @var Discord */
  private $discord;

  /** @var int */
  private $userId;

  /** @var CommandCollection */
  private $commandBindings;

  /** @var MessageMatcher */
  private $rootCommandBinding;

  /**
   * @param Discord $discord
   * @param int $userId
   * @param CommandCollection $commandBindings
   */
  public function __construct(Discord $discord, $userId, CommandCollection $commandBindings) {
    $this->discord = $discord;
    $this->userId = $userId;
    $this->commandBindings = $commandBindings;
    $this->rootCommandBinding = new AndMessageMatcher(
      [
        new NotFromMessageMatcher($this->userId),
        new OrMessageMatcher(
          [
            PrivateMessageMatcher::instance(),
            new MentionsMessageMatcher($userId, 'Gen Bot')
          ]
        )
      ]
    );

    $this->discord->on('ready', function(Discord $discord) {
      $this->onReady();
    });
  }

  /**
   * @return CommandCollection
   */
  public function getCommandBindings() {
    return $this->commandBindings;
  }

  /**
   * @param User $user
   * @return Guild[]
   */
  public function getAllGuildsWithUser(User $user) {
    return array_values(
      array_filter(
        iterator_to_array($this->discord->guilds),
        function(Guild $guild) use ($user) {
          return $this->userIsInGuild($user, $guild);
        }
      )
    );
  }

  /**
   * @param User $user
   * @param Guild $guild
   * @return boolean
   */
  private function userIsInGuild(User $user, Guild $guild) {
    foreach ($guild->members as $member) {
      /** @var Member $member */
      if ($member->user->id == $user->id) {
        return true;
      }
    }

    return false;
  }

  private function onReady() {
    $this->log('Bot is ready');
    $this->discord->on(
      'message',
      function (Message $message, Discord $discord) {
        $this->onMessage($discord, $message);
      }
    );
  }

  public function run() {
    $this->discord->run();
  }

  /**
   * @param Discord $discord
   * @param Message $message
   */
  private function onMessage(Discord $discord, Message $message) {
    $this->log(sprintf('Message received: %s', $message->content));
    
    if (!$this->rootCommandBinding->matches($message, $this)) {
      $this->log(' not directed to me');
      return;
    }
    
    foreach ($this->commandBindings->getMatchers() as $matcher) {
      if ($matcher->matches($message, $this)) {
        $command = $this->commandBindings->offsetGet($matcher);
        
        try {
          $command->execute($this, $message);
        } catch (\Exception $e) {
          $this->log('Gen Bot Error: ' . $e->getMessage());
        }
        return;
      }
    }

    $this->log(' no matching commands');
  }

  /**
   * @param string $message
   */
  private function log($message) {
    echo $message . "\n";
  }

  /**
   * @param string $token
   * @param int $id
   * @return DiscordBotBuilder
   */
  public static function builder($token, $id) {
    return new DiscordBotBuilder($token, $id);
  }
}