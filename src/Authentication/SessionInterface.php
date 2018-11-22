<?php

namespace Shapecode\FUT\Authentication;

/**
 * Interface SessionInterface
 *
 * @package Shapecode\FUT\Authentication
 * @author  Shapecode
 */
interface SessionInterface
{

    /**
     * @return string
     */
    public function getPersona();

    /**
     * @return string
     */
    public function getNucleus();

    /**
     * @return string
     */
    public function getPhishing();

    /**
     * @return string
     */
    public function getSession();

    /**
     * @return string
     */
    public function getDob();

    /**
     * @return string
     */
    public function getAccessToken();

    /**
     * @return string
     */
    public function getTokenType();

    /**
     * @return \DateTime|null
     */
    public function getExpiresAt();
}
