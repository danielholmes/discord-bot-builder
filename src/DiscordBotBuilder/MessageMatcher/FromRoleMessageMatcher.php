<?php

namespace DiscordBotBuilder\MessageMatcher;

use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;
use DiscordBotBuilder\DiscordBot;

class FromRoleMessageMatcher implements MessageMatcher
{
  /** @var int */
  private $id;

  /** @var string */
  private $name;

  /**
   * @param int $id
   * @param string $name
   */
  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }

  /**
   * @inheritdoc
   */
  public function matches(Message $message, DiscordBot $bot) {
    $guilds = [];
    if (PrivateMessageMatcher::instance()->matches($message, $bot)) {
      $guilds = $bot->getAllGuildsWithUser($message->author);
    } else {
      $guilds = [$message->channel->guild];
    }

    // This is a bit weird since roles are tied to guilds. Maybe need to tie this perm to a particular guild also
    // Might require an overhaul of all the matching

    foreach ($guilds as $guild) {
      /** @var Guild $guild */
      foreach ($guild->members as $member) {
        /** @var Member $member */
        if ($member->user->id == $message->author->id) {
          foreach ($member->roles as $role) {
            /** @var Role $role */
            if ($role->id == $this->id) {
              return true;
            }
          }
        }
      }
    }

    return false;
  }
}