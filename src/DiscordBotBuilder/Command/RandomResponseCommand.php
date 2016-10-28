<?php


namespace DiscordBotBuilder\Command;

use Discord\Parts\Channel\Message;
use DiscordBotBuilder\DiscordBot;

class RandomResponseCommand implements Command
{
  /** @var string[] */
  private $responses;

  /** @var string */
  private $template;
  
  /** @var string */
  private $description;

  /**
   * @param string[] $responses
   * @param string $description
   * @param string $template
   */
  public function __construct(array $responses, $description, $template = '%s') {
    if (substr_count($template, '%s') !== 1) {
      throw new \InvalidArgumentException('Template needs exactly one %s');
    }
    $this->responses = $responses;
    $this->description = $description;
    $this->template = $template;
  }

  /**
   * @param DiscordBot $bot
   * @param Message $message
   */
  public function execute(DiscordBot $bot, Message $message) {
    if (empty($this->responses)) {
      return;
    }
    $response = $this->responses[array_rand($this->responses)];
    $message->reply(sprintf($this->template, $response));
  }

  /**
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }
}