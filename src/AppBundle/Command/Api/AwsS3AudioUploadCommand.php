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


use AppBundle\Service\Core\Aws\AwsUploadAudioService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class AwsS3AudioUploadCommand extends ContainerAwareCommand
{

    /**
     * @var AwsUploadAudioService
     */
    private $awsS3Service;


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
        $this->awsS3Service =$this->getContainer()->get('app.core.aws.upload.audio');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('api:aws:s3:upload ')
            ->setDescription('Upload audio to aws bucket');

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = '/Users/mfana/Sites/local-content/web/uploads/Metro_Promo_Hookup_With_Us_Online_twitter_Website.mp3';

        $this->awsS3Service->uploadSlogan($file);


    }

}
