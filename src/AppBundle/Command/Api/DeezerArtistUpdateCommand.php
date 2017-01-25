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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Service\Artist\ArtistService;
use Symfony\Component\Console\Question\Question;

class DeezerArtistUpdateCommand extends ContainerAwareCommand
{
    /**
     * @var ArtistService
     */
    private $artistService;

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
        $this->artistService = $this->getContainer()->get('app.service.artist');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:deezer-artist-update')
            ->setDescription('Update artist with deezer details')
            ->addArgument('deezerId', InputArgument::REQUIRED, 'Artist deezer id');
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('deezerId')) {
            return;
        }

        // multi-line messages can be displayed this way...
        $output->writeln('');
        $output->writeln('Update Artist Deezer Command Interactive Wizard');
        $output->writeln('-----------------------------------');

        // ...but you can also pass an array of strings to the writeln() method
        $output->writeln([
            '',
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:deezer-artist-update deezerId',
            '',
        ]);

        // See http://symfony.com/doc/current/components/console/helpers/questionhelper.html
        $console = $this->getHelper('question');

        // Ask for the username if it's not defined
        $deezerId = $input->getArgument('deezerId');
        if (null === $deezerId) {
            $question = new Question(' > <info>deezerId</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The deezerId cannot be empty');
                }

                return $answer;
            });

            $deezerId = $console->ask($input, $output, $question);
            $input->setArgument('deezerId', $deezerId);
        } else {
            $output->writeln(' > <info>DeezerId</info>: '.$deezerId);
        }
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $deezerId= $input->getArgument('deezerId');

        /**
         * Find Artist by deezerId
         */
        $artist = $this->artistService->getByDeezerId($deezerId);

        if(!$artist){
            $output->writeln(sprintf('[ERROR] Artist was not found with deezerId: %s', $deezerId));
        }else{
            try{
                $this->artistService->deezerUpdate($artist);
                $output->writeln(sprintf('[OK] artist %s was successfully updated ',$artist->getTitle()));
            }catch (\Exception $e){
                $output->writeln(sprintf('[ERROR] Error occurred whilst updating artist, try again later. error: %s', $e->getMessage()));
            }

        }

    }

}
