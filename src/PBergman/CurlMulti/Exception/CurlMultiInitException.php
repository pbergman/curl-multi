<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti\Exception;

/**
 * Class CurlMultiInitException
 *
 * @package PBergman\CurlMulti\Exception
 */
class CurlMultiInitException extends CurlException
{
    public function __construct()
    {
        parent::__construct("failed to initialize the curl multi handler");
    }
}
