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

namespace AppBundle\Service\Core\Apostle;


use Psr\Log\LoggerInterface;
use Unirest\Request;

class ApostleRestFulClientService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var String
     */
    private $url;

    /**
     * @var Array
     */
    private $headers;

    /**
     * ApostleRestFulClientService constructor.
     * @param LoggerInterface $logger
     * @param String $url
     */
    public function __construct(LoggerInterface $logger, $url)
    {
        $this->logger = $logger;
        $this->url = $url;
    }

    /**
     * Queue file upload request task
     *
     * @param $contentType
     * @param $apiType
     * @param $fileId
     * @return \Unirest\Response
     */
    public function queueFileUpload($contentType,$apiType,$fileId)
    {
        $this->headers = array('Accept' => 'application/json');
        $query = array('contentType' => $contentType, 'apiType' => $apiType,'fileId' => $fileId);

        $response = Request::post($this->url.'/api/v1/internal/upload/file',$this->headers,$query);
        return $response;
    }

    /**
     * Queue failed task request, for re-submit
     * @param $correlationId
     * @param $data
     * @return \Unirest\Response
     */
    public function queueFailedTask($correlationId,$data)
    {
        $this->headers = array('Accept' => 'application/json');
        $query = array('correlationId' => $correlationId, 'error' => $data);
        $response = Request::post($this->url.'/api/v1/internal/failed/task',$this->headers,$query);
        return $response;

    }

    /**
     * Queue stream processing
     * @param $streamId
     * @param $processType
     * @return \Unirest\Response
     */
    public function queueStreamProcessingTask($streamId,$processType)
    {
        $this->headers = array('Accept' => 'application/json');
        $query = array('streamId' => $streamId, 'processType' => $processType);
        $response = Request::post($this->url.'/api/v1/internal/stream/process',$this->headers,$query);
        return $response;
    }

    /**
     * @param $asyncType
     * @param $apiType
     * @param $entityId
     * @param $month
     * @return \Unirest\Response
     */
    public function queueAsynchronousProcessingTask($asyncType,$apiType,$entityId,$month)
    {
        $this->headers = array('Accept' => 'application/json');
        $query = array('asyncType' => $asyncType, 'apiType' => $apiType,'entityId' => $entityId, 'month' => $month);
        $response = Request::post($this->url.'/api/v1/internal/asynchronous/process',$this->headers,$query);
        return $response;
    }
}