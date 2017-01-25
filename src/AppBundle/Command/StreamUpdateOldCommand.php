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
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use AppBundle\Service\Song\SongService;
use Symfony\Component\Console\Question\Question;

class StreamUpdateOldCommand extends ContainerAwareCommand
{
    /**
     * @var RadioStationStreamService
     */
    private $radioStationStreamService;

    /**
     * @var SongService
     */
    private $songService;

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
        $this->radioStationStreamService = $this->getContainer()->get('app.service.radio_station_stream');
        $this->songService = $this->getContainer()->get('app.service.song');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:update-stream old records')
            ->setDescription('Update streams with song Ids based on ISRC code')
            ->addArgument('isrc', InputArgument::OPTIONAL, 'Song ISRC code');
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
        if (null !== $input->getArgument('isrc')) {
            return;
        }

        // multi-line messages can be displayed this way...
        $output->writeln('');
        $output->writeln('Update stream Command Interactive Wizard');
        $output->writeln('-----------------------------------');

        // ...but you can also pass an array of strings to the writeln() method
        $output->writeln([
            '',
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:update-stream isrc',
            '',
        ]);

        // See http://symfony.com/doc/current/components/console/helpers/questionhelper.html
        $console = $this->getHelper('question');

        // Ask for the username if it's not defined
        $isrc = $input->getArgument('isrc');
        if (null === $isrc) {
            $question = new Question(' > <info>ISRC</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The ISRC cannot be empty');
                }

                return $answer;
            });

            $isrc = $console->ask($input, $output, $question);
            $input->setArgument('isrc', $isrc);
        } else {
            $output->writeln(' > <info>ISRC</info>: '.$isrc);
        }
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isrc = $input->getArgument('isrc');

        /**
         * Find song by ISRC
         */
        $song = $this->songService->getByISRC($isrc);

        if(!$song){
            $output->writeln(sprintf('[ERROR] Song was not found with ISRC: %s', $isrc));
        }else{
            $count = $this->radioStationStreamService->updateStreamsWithSongDetails($song);
            $output->writeln(sprintf('[OK] %s records were updated for : %s',$count , $isrc));
        }

    }

}
