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
namespace AppBundle\Event\Song;

class SongEvents
{
    const UPLOAD = 'mlanka_tech_app.on_song_upload';
    const UPLOAD_APPROVE = 'mlanka_tech_app.on_song_upload_approve';
    const RADIO_STATION_INCOMING_APPROVE = 'mlanka_tech_app.on_song_approve_incoming_radio_station_queue';
}