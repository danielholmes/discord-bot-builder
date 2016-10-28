<?php

namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class RandomImageCommand implements Command
{
  /** @var string */
  private $rootUrl;

  /** @var string */
  private $directory;

  /** @var string */
  private $description;

  /** @var string[] */
  private $files;

  /**
   * @param string $rootUrl
   * @param string $directory
   */
  public function __construct($rootUrl, $directory, $description) {
    $this->rootUrl = $rootUrl;
    $this->directory = $directory;
    $this->description = $description;
  }

  /**
   * @inheritdoc
   */
  public function execute(DiscordBot $bot, Message $message) {
    $imageFileName = $this->getRandomFileName();
    $message->reply($this->rootUrl . '/' . rawurlencode($imageFileName));
  }
  
  private function getRandomFileName() {
    if ($this->files === null) {
      $this->files = array_diff(scandir($this->directory), array('.', '..'));
    }
    if (empty($this->files)) {
      return null;
    }
    
    return $this->files[array_rand($this->files)];
  }

  /**
   * @inheritdoc
   */
  public function getDescription() {
    return $this->description;
  }
}
