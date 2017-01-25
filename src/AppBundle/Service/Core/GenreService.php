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
namespace AppBundle\Service\Core;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Genre;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class GenreService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GenreService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     */
    public function __construct(LoggerInterface $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * Find genre by title
     * @param $name
     * @return \AppBundle\Entity\Genre|null|object
     */
    public function findByGenre($name)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": find by genre :".$name);

        return  $this->em->getRepository('AppBundle:Genre')->findOneBy(array('title'=>strtolower($name)));
    }

    /**
     * Create genre
     *
     * @param $name
     * @return Genre
     */
    public function create($name)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": create genre :".$name);
        $genre = new Genre(strtolower($name));
        $this->em->persist($genre);
        $this->em->flush();
        return $genre;
    }

    /**
     * Search for a genre and create new one if not found
     *
     * @param $name
     * @return Genre|null|object
     */
    public function searchForGenreOrCreate($name)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": search for genre, or create a new one :".$name);

        $genre = $this->findByGenre($name);
        if(!$genre){
            $genre = $this->create($name);
        }

        return $genre;
    }

}