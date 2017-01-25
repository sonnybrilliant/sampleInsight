<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/20
 */
namespace AppBundle\Interfaces;

/**
 * Interface ActivityStreamInterface
 * @package AppBundle\Interfaces
 */
interface ActivityStreamInterface
{
    /**
     * Called when creating a new feed instance on API
     *
     * @param int $id
     * @return void
     */
    public function create($id);

    /**
     * Post payload to API for particular feed
     *
     * @param int $id
     * @param $payload
     * @return mixed
     */
    public function post($id,$payload);

    /**
     * Get activity
     *
     * @param int $id
     * @param int $limit
     * @return mixed
     */
    public function getActivity($id,$limit = 10);

}