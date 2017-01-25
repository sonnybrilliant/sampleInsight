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

namespace AppBundle\Service\Core\Acrcloud;

use AppBundle\Common\ContentType;
use Psr\Log\LoggerInterface;

class AcrcloudUploadAudioService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var
     */
    private $apiUrl;

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
    private $signature;

    /**
     * @var Mixed
     */
    private $arrHeader;

    /**
     * @var String
     */
    private $timestamp;

    /**
     * AcrcloudUploadAudioService constructor.
     *
     * @param LoggerInterface $logger
     * @param $apiUrl
     * @param String $key
     * @param String $secret
     * @param String $bucketSlogan
     * @param String $bucketAdvert
     * @param String $bucketPromo
     */
    public function __construct(LoggerInterface $logger, $apiUrl, $key, $secret, $bucketSlogan, $bucketAdvert, $bucketPromo)
    {
        $this->logger = $logger;
        $this->apiUrl = $apiUrl;
        $this->key = $key;
        $this->secret = $secret;
        $this->bucketSlogan = $bucketSlogan;
        $this->bucketAdvert = $bucketAdvert;
        $this->bucketPromo = $bucketPromo;
    }


    /**
     *  Initialize service
     */
    private function init()
    {
        $this->timestamp = time();
        $this->signature = $this->getSignature();
        $this->arrHeader = $this->getHeader();
    }

    /**
     * Get signature
     *
     * @return string
     */
    private function getSignature()
    {
        $httpMethod = "POST";
        $httpUri = "/v1/audios";

        $signatureVersion = '1';

        $stringToSign = $httpMethod . "\n" .
            $httpUri . "\n" .
            $this->key . "\n" .
            $signatureVersion . "\n" .
            $this->timestamp;

        $signature = hash_hmac("sha1",$stringToSign,$this->secret,true);
        $signature = base64_encode($signature);
        return $signature;
    }

    /**
     * Get Header
     * @return array
     */
    private function getHeader()
    {
        $headers = array(
            'access-key' => $this->key,
            'timestamp' => $this->timestamp,
            'signature-version' => '1',
            'signature' => $this->signature
        );
        $arrTmp = array();
        foreach($headers as $n => $v) {
            $arrTmp[] = $n .':' . $v;
        }
        return $arrTmp;
    }

    /**
     * Get post fields
     *
     * @param $arrParam
     * @return array
     */
    private function getPostFields($arrParam)
    {
        /**
         * Rule out know keys to dynamically add ContentType specific ones
         */
        $knownKeys = array(
            'title',
            'artist',
            'file',
            'code');

        $payload = array(
            'audio_file' => new \CURLFile($arrParam['file'], "audio/mp3", basename($arrParam['file'])),
            'title' => $arrParam['title'],
            'audio_id' => $arrParam['code'],
            'bucket_name' => '',
            'data_type'=>'audio',  // if you upload fingerprint file please set 'data_type'=>'fingerprint'
            'custom_key[0]' => 'artist',
            'custom_value[0]' => $arrParam['artist'],
        );

        /**
         * Check for ContentType Specific fields
         */
        $counter = 1;

        foreach($arrParam as $key=>$value){
            if(!in_array($key,$knownKeys)){
                $payload["custom_key[".$counter."]"] = $key;
                $payload["custom_value[".$counter."]"] = $value;
                $counter++;
            }
        }

        return $payload;
    }

    /**
     * Upload content file to server
     *
     * @param $postFields
     * @return mixed
     */
    private function upload($postFields)
    {
        $this->init();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->arrHeader);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Prepare content to upload
     *
     * @param $arrParam
     * @return mixed
     */
    public function uploadContent($arrParam)
    {
        $postFields = $this->getPostFields($arrParam);

        if($arrParam['type'] == ContentType::PROMOTION){
            $postFields['bucket_name'] = $this->bucketPromo;
        }elseif($arrParam['type'] == ContentType::SLOGAN){
            $postFields['bucket_name'] = $this->bucketSlogan;
        }elseif ($arrParam['type'] == ContentType::ADVERT){
            $postFields['bucket_name'] = $this->bucketAdvert;
        }

        return $this->upload($postFields);
    }

}