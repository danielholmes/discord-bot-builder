<?php

namespace DiscordBotBuilder\Discord;

use GuzzleHttp\Client;

class UpdateBotProfile
{
  /**
   * @param string $token
   * @param string $name
   * @param string $imagePath
   * @param int $guildId
   * @param int $userId
   * @param string $nickname
   * @return string
   */
  public static function update($token, $name, $imagePath, $guildId, $userId, $nickname) {
    $userOutput = self::updateUser($token, $name, $imagePath);
    $memberOutput = ''; // error atm self::updateMember($token, $guildId, $userId, $nickname);

    return $userOutput . "\n" . $memberOutput;
  }

  /**
   * @param string $token
   * @param int $guildId
   * @param int $userId
   * @param string $nickname
   * @return string
   */
  private static function updateMember($token, $guildId, $userId, $nickname) {
    $body = [
      'nick' => $nickname
    ];

    $client = new Client();
    $res = $client->patch(
      'https://discordapp.com/api/guilds/' . $guildId . '/members/' . $userId,
      [
        'Authorization' => 'Bot ' . $token,
        'body' => $body
      ]
    );

    if ($res->getStatusCode() !== 204) {
      throw new \RuntimeException('Error: ' . $res->getStatusCode() . ': ' . $res->getBody());
    }

    return $res->getBody();
  }

  /**
   * @param string $token
   * @param string $name
   * @param string $imagePath
   * @return string
   */
  private static function updateUser($token, $name, $imagePath) {
    if (!is_readable($imagePath)) {
      throw new \InvalidArgumentException('Can\'t find image: ' . $imagePath);
    }

    $newBase64Image = base64_encode(file_get_contents($imagePath));
    $body = [
      'username' => $name,
      'avatar' => 'data:image/jpeg;base64,' . $newBase64Image
    ];

    $client = new Client();
    $res = $client->patch(
      'https://discordapp.com/api/users/@me',
      [
        'Authorization' => 'Bot ' . $token,
        'body' => $body
      ]
    );

    if ($res->getStatusCode() !== 200) {
      throw new \RuntimeException('Error: ' . $res->getStatusCode() . ': ' . $res->getBody());
    }

    return $res->getBody();
  }
}