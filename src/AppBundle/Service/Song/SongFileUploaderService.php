<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/02
 */
namespace AppBundle\Service\Song;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SongFileUploaderService
{
    /**
     * @var String
     */
    private $targetDir;

    /**
     * SongFileUploaderService constructor.
     * @param $targetDir
     */
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $ext = 'mp3';
        $fileName = '';

        if("mp3" == $file->guessExtension()){
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        }else{
            $fileName = md5(uniqid()) . '.' . $ext;
        }

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }
}