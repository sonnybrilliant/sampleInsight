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
namespace AppBundle\Command\Common;


use AppBundle\Handler\Form\Archive\ArchiveCreateHandler;
use AppBundle\Service\RadioShow\RadioShowService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

class CreateRadioShowTimeSlotCommand extends ContainerAwareCommand
{

    /**
     * @var RadioShowService
     */
    private $radioShowService;


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
        $this->radioShowService =$this->getContainer()->get('app.service.radio_show');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('system:radioShowTimeSlot ')
            ->setDescription('Create time slot for radio show')
            ->addArgument('showId', InputArgument::REQUIRED, 'Radio show Id')
            ->addArgument('month', InputArgument::REQUIRED, 'Time slot month');

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
        if (null !== $input->getArgument('showId') && null !== $input->getArgument('month')) {
            return;
        }


        // multi-line messages can be displayed this way...
        $output->writeln('');
        $output->writeln('Create radio show time slots Command Interactive Wizard');
        $output->writeln('-----------------------------------');

        // ...but you can also pass an array of strings to the writeln() method
        $output->writeln([
            '',
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console system:radioShowTimeSlot showId month',
            '',
        ]);

        // See http://symfony.com/doc/current/components/console/helpers/questionhelper.html
        $console = $this->getHelper('question');

        // Ask for showId if it's not defined
        $showId = $input->getArgument('showId');
        if (null === $showId) {
            $question = new Question(' > <info>ShowId</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The ShowId cannot be empty');
                }

                return $answer;
            });

            $showId = $console->ask($input, $output, $question);
            $input->setArgument('showId', $showId);
        } else {
            $output->writeln(' > <info>ShowId</info>: '.$showId);
        }

        // Ask for showId if it's not defined
        $month = $input->getArgument('month');
        if (null === $month ) {
            $question = new Question(' > <info>Month</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The Month cannot be empty');
                }

                return $answer;
            });

            $month = $console->ask($input, $output, $question);
            $input->setArgument('month', $month);
        } else {
            $output->writeln(' > <info>Month</info>: '.$month);
        }
    }



    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $showId = $input->getArgument('showId');
        $month = $input->getArgument('month');

        $this->radioShowService->createTimeSlot($showId,$month);
    }

}
