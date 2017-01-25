<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/01
 */
namespace AppBundle\Command\Api;

use AppBundle\Service\Slogan\SloganService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use AppBundle\Service\Core\Acrcloud\AcrcloudUploadAudioService;
use Symfony\Component\Console\Question\Question;

class AcrcloudAudioUploadCommand extends ContainerAwareCommand
{

    /**
     * @var SloganService
     */
    private $sloganService;

    /**
     * @var AcrcloudAudioUpload
     */
    private $acrcloudUploadAudioService;

    /**
     * This method is executed before the interact() and the execute() methods.
     * It's main purpose is to initialize the variables used in the rest of the
     * command methods.
     *
     * Beware that the input options and arguments are validated after executing
     * the interact() method, so you can't blindly trust their values in this method.
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->sloganService =$this->getContainer()->get('app.service.slogan');
        $this->acrcloudUploadAudioService =$this->getContainer()->get('app.core.acrcloud.upload.audio');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('api:upload ')
            ->setDescription('Upload slogan to acrcloud bucket');

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $slogan = $this->sloganService->getById(2);

        $arrParam = array(
            'file' => $slogan->getRealFilePath(),
            'artist' => 'Ronald',
            'album' => 'slogan',
            'type' => 'slogan',
            'code' => $slogan->getCode(),
            'title' => 'This is a test by ronald'
        );

        $this->acrcloudUploadAudioService->uploadSlogan($arrParam);
    }

}
