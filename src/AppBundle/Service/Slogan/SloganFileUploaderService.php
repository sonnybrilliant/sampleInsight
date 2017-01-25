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
namespace AppBundle\Service\Slogan;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SloganFileUploaderService
{
    /**
     * @var String
     */
    private $targetDir;

    /**
     * FileUploaderService constructor.
     * @param $targetDir
     */
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @return array
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

        return array(
            'fileName' => $fileName,
            'dir' => $this->targetDir,
        );
    }
}