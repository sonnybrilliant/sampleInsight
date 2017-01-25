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
namespace AppBundle\Service\Artist;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Artist;
use AppBundle\Entity\RadioStationStream;
use AppBundle\Service\Core\Deezer\DeezerLookUpService;
use AppBundle\Service\Core\GenreService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;

class ArtistService
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
     * @var StatusServices
     */
    private $status;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var DeezerLookUpService
     */
    private $deezerLookupService;

    /**
     * @var GenreService
     */
    private $genreService;

    /**
     * ArtistService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $status
     * @param UserService $userService
     * @param DeezerLookUpService $deezerLookupService
     * @param GenreService $genreService
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, StatusServices $status, UserService $userService, DeezerLookUpService $deezerLookupService, GenreService $genreService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
        $this->deezerLookupService = $deezerLookupService;
        $this->genreService = $genreService;
    }

    /**
     * @param $id
     * @return Artist|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:Artist')->find($id);
    }

    /**
     * @param $deezerId
     * @return Artist|null|object
     */
    public function getByDeezerId($deezerId)
    {
        return $this->em->getRepository('AppBundle:Artist')->findOneBy(array('apiDeezerId'=>$deezerId));
    }

    /**
     * @param Artist $artist
     * @return Artist
     */
    public function create(Artist $artist)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": artist create:".$artist->getTitle());

        if(!$artist->getCreatedBy()){
            $artist->setCreatedBy($this->userService->getLoggedInUser());
        }

        $artist->setStatus($this->status->active());
        $this->em->persist($artist);
        $this->em->flush();
        return $artist;
    }

    /**
     * @param Artist $artist
     * @return Artist
     */
    public function update(Artist $artist)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": artist update:".$artist->getTitle());
        $this->em->persist($artist);
        $this->em->flush();
        return $artist;
    }

    /**
     * Deezer Artist update
     *
     * @param Artist $artist
     * @return Artist
     */
    public function deezerUpdate(Artist $artist)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": deezer artist update");

        $deezerId = $artist->getApiDeezerId();
        if($deezerId){
            try{
                $results = $this->deezerLookupService->artist($deezerId);

                if(isset($results->picture_small)){
                    $artist->setApiDeezerImage56x56($results->picture_small);
                }

                if(isset($results->picture_medium)){
                    $artist->setApiDeezerImage250x250($results->picture_medium);
                }

                if(isset($results->picture_big)){
                    $artist->setApiDeezerImage500x500($results->picture_big);
                }

                if(isset($results->picture_xl)){
                    $artist->setApiDeezerImage500x500($results->picture_xl);
                }
                $artist->setIsDeezerProcessed(true);
                $this->update($artist);

            }catch(\Exception $e){
                $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed deezer artist update error:".$e->getMessage());
            }
        }
        return $artist;
    }

    /**
     * Process artist from stream
     *
     * @param RadioStationStream $radioStationStream
     */
    public function processArtistFromStream(RadioStationStream $radioStationStream)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": process artist from stream");

        $artistName = $radioStationStream->getArtist();
        //check for artist by name
        $artist = $this->em->getRepository('AppBundle:Artist')->findOneBy(array('title'=>$artistName));
        if(!$artist){
            if(!is_null($artistName) || strlen($artistName) > 2){
                //if not found, create it
                $isDeezerAvailable = false;

                $artist = new Artist();
                $artist->setTitle($artistName);
                $artist->setStatus($this->status->notVerified());

                if($radioStationStream->getData()){
                    $objData = (object) $radioStationStream->getData();
                    if(isset($objData->metadata["music"][0]["external_metadata"]["deezer"]["artists"][0]["id"])){
                        $artist->setApiDeezerId($objData->metadata["music"][0]["external_metadata"]["deezer"]["artists"][0]["id"]);
                        $isDeezerAvailable = true;
                    }

                    //process genres
                    if(isset($objData->metadata["music"][0]["genres"])){
                        if(is_array($objData->metadata["music"][0]["genres"])){
                            $genres = $objData->metadata["music"][0]["genres"];
                            $toAddGenres = new ArrayCollection();
                            for($x = 0; $x < count($genres); $x++){
                                $genre = $this->genreService->searchForGenreOrCreate($genres[$x]['name']);
                                if($genre){
                                    $toAddGenres->add($genre);
                                }
                            }
                            if($toAddGenres->count() > 0){
                                $artist->setGenres($toAddGenres);
                            }
                        }
                    }
                }

                //save
                $this->em->persist($artist);
                $this->em->flush();

                if($isDeezerAvailable){
                    //deezer update
                    try{
                        $this->deezerUpdate($artist);
                    }catch (\Exception $e){
                        $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to   process deezer update on artist:".$artist->getTitle());
                    }
                }
            }
        }

        if($artist){
            $radioStationStream->setArtistObject($artist);
            //if it's a verified artist, use it's data.
            if($artist->getStatus()->getCode() != $this->status->notVerified()){
                $radioStationStream->setIsLocal($artist->getIsLocal());
                $radioStationStream->setIsAfrican($artist->getIsAfrican());
            }

            $this->em->persist($radioStationStream);
            $this->em->flush();
        }
    }

}