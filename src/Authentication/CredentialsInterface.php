<?php

namespace Shapecode\FUT\Authentication;

/**
 * Interface CredentialsInterface
 *
 * @package Shapecode\FUT\Authentication
 * @author  Shapecode
 */
interface CredentialsInterface
{

    const VALID_PLATFORMS = [
        'pc',
        'xbox',
        'xbox360',
        'ps3',
        'ps4',
    ];

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return string
     */
    public function getPlatform();

    /**
     * @return string
     */
    public function getEmulate();

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @return string
     */
    public function getCountry();

}
