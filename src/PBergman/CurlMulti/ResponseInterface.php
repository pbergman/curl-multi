<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti;

/**
 * Interface ResponseInterface
 *
 * @package PBergman\CurlMulti
 */
interface ResponseInterface
{
    /**
     * get info from curl handle, see: curl_getinfo
     *
     * @param null $opt
     * @return mixed
     */
    public function getInfo($opt = null);

    /**
     * get response test of request
     *
     * @return string
     */
    public function getContent();

    /**
     * return any encountered errors
     *
     * @return string
     */
    public function getError();

    /**
     * check for any encountered errors
     *
     * @return bool
     */
    public function hasError();

    /**
     * @return resource
     */
    public function getHandle();

    /**
     * @return RequestInterface
     */
    public function getRequest();
}
