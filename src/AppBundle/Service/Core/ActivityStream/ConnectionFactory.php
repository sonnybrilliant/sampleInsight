<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/21
 */

namespace AppBundle\Service\Core\ActivityStream;

use GetStream\Stream\Client;

class ConnectionFactory
{
    /**
     * @var Client
     */
    protected static $connection;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $region = 'eu-central';

    /**
     * Get connection
     *
     * @return Client
     */
    public function getConnection()
    {
      if(!self::$connection){
          $client = new Client($this->key,$this->secret);
          $client->setLocation($this->region);
          self::$connection = $client;
      }
      return self::$connection;
    }
}