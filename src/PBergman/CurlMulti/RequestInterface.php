<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti;

/**
 * Interface RequestInterface
 *
 * @package PBergman\CurlMulti
 */
interface RequestInterface
{
    /**
     * should return an array that can be used
     * for the curl_setopt_array function
     *
     * @return array
     */
    public function getOptions();

    /**
     * @param int $opt
     * @return mixed
     */
    public function getOption($opt);

    /**
     * @param int $result
     * @param resource $handle
     * @return ResponseInterface
     */
    public function handle($result, $handle);
}