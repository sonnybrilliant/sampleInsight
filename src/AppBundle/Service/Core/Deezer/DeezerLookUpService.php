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

namespace AppBundle\Service\Core\Deezer;

use AppBundle\Common\FileUtil;
use Psr\Log\LoggerInterface;

class DeezerLookUpService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var String
     */
    private $apiUrl;

    /**
     * DeezerArtistLookUpService constructor.
     * @param LoggerInterface $logger
     * @param String $apiUrl
     */
    public function __construct(LoggerInterface $logger, $apiUrl)
    {
        $this->logger = $logger;
        $this->apiUrl = $apiUrl;
    }

    /**
     * Look up artist by Id
     *
     * @param $deezerId
     * @return mixed
     * @throws \Exception
     */
    public function artist($deezerId)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": lookup deezer artist id:".$deezerId);

        $query = $this->apiUrl.'/artist/'.$deezerId;

        $file = file_get_contents($query);

        if(!$file){
            $str = $this->apiUrl;
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Failed to connect to deezer api url:".$str);
            throw new \Exception("Failed to connect to deezer api url:".$str);
        }
        return json_decode($file);
    }


}