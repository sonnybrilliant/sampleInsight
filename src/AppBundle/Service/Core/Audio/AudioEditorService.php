<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */
namespace AppBundle\Service\Core\Audio;


use FFMpeg\FFMpeg;
use Psr\Log\LoggerInterface;

class AudioEditorService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Resource
     */
    private $ffmpeg = null;

    /**
     * AudioEditorService constructor.
     * @param LoggerInterface $logger
     * @param String $ffmpegBin
     * @param String $ffprobeBin
     */
    public function __construct(
        LoggerInterface $logger,
        $ffmpegBin,
        $ffprobeBin)
    {
        $this->logger = $logger;

        $this->ffmpeg = FFMpeg::create(array(
            'ffmpeg.binaries'  => $ffmpegBin,
            'ffprobe.binaries' => $ffprobeBin,
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ), $logger);

    }

    /**
     * Get audio details
     *
     * @param $file
     * @return array
     */
    public function getDetails($file){
        $audio = $this->ffmpeg->open($file);
        return $audio->getFormat()->all();
    }



}