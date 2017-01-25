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

namespace AppBundle\Service\Core\Aws;

use AppBundle\Common\ContentType;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Psr\Log\LoggerInterface;

class AwsUploadAudioService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var String
     */
    private $key;

    /**
     * @var String
     */
    private $secret;

    /**
     * @var String
     */
    private $bucketSlogan;

    /**
     * @var String
     */
    private $bucketAdvert;

    /**
     * @var String
     */
    private $bucketPromo;

    /**
     * @var String
     */
    private $bucketHourRecordings;

    /**
     * @var string
     */
    private $region = 'eu-west-1';

    /**
     * @var Object
     */
    private $s3;

    /**
     * AwsUploadAudioService constructor.
     * @param LoggerInterface $logger
     * @param String $key
     * @param String $secret
     * @param String $bucketSlogan
     * @param String $bucketAdvert
     * @param String $bucketPromo
     * @param String $bucketHourRecordings
     */
    public function __construct(LoggerInterface $logger, $key, $secret, $bucketSlogan, $bucketAdvert, $bucketPromo, $bucketHourRecordings)
    {
        $this->logger = $logger;
        $this->key = $key;
        $this->secret = $secret;
        $this->bucketSlogan = $bucketSlogan;
        $this->bucketAdvert = $bucketAdvert;
        $this->bucketPromo = $bucketPromo;
        $this->bucketHourRecordings = $bucketHourRecordings;
    }


    /**
     * Initial S3 client
     * @return S3Client
     */
    private function init()
    {
        $s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'credentials' => [
                'key'    => $this->key,
                'secret' => $this->secret,
            ],
        ]);

        return $s3Client;
    }

    /**
     * Upload content
     *
     * @param $file
     * @param $contentType
     * @return mixed|null
     */
    public function uploadContent($file,$contentType)
    {
        $results = null;
        if(ContentType::PROMOTION == $contentType){
            $results = $this->upload($this->bucketPromo,$file);
        }elseif (ContentType::SLOGAN == $contentType){
            $results = $this->upload($this->bucketSlogan,$file);
        }elseif (ContentType::ADVERT == $contentType){
            $results = $this->upload($this->bucketAdvert,$file);
        }elseif (ContentType::ARCHIVE == $contentType){
            $results = $this->upload($this->bucketHourRecordings,$file);
        }

        return $results;
    }

    /**
     * Upload file to S3
     *
     * @param $bucket
     * @param $file
     * @return mixed
     */
    private function upload($bucket,$file)
    {
        $client = $this->init();
        $key = basename($file);
        $response = $client->upload($bucket,$key,file_get_contents($file),'public-read');
        return $response;
    }
}