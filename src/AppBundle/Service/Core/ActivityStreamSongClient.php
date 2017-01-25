<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/06
 */

namespace AppBundle\Service\Core;

use App\Exception\NoActivityConnectionException;
use AppBundle\Interfaces\ActivityStreamInterface;
use GetStream\Stream\Client;

class ActivityStreamSongClient implements ActivityStreamInterface
{
    /**
     * @var string
     */
    private $key = '';

    /**
     * @var string
     */
    private $secret = '';

    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var bool
     */
    private $isConnected = false;

    /**
     * ActivityStreamClient constructor.
     *
     * @param string $key
     * @param string $secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * Connect to API ()
     *
     * @return $this
     */
    private function init()
    {
        $this->client = new Client($this->key,$this->secret);

        if($this->client){
            $this->client->setLocation("eu-central");
            $this->isConnected = true;
        }

        return $this;
    }

    /**
     * Called when creating a new feed instance on API
     *
     * @param int $id
     * @return void
     */
    public function create($id)
    {
        if($this->isConnected){

        }else{

        }
    }

    /**
     * Post payload to API for particular feed
     *
     * @param int $id
     * @param $payload
     * @return mixed
     */
    public function post($id, $payload)
    {
        // TODO: Implement post() method.
    }

    /**
     * Get activity
     *
     * @param int $id
     * @param int $limit
     * @return mixed
     */
    public function getActivity($id, $limit = 10)
    {
        // TODO: Implement getActivity() method.
    }


}