<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti\Exception;

/**
 * Class CurlErrorException
 *
 * @package PBergman\CurlMulti\Exception
 */
class CurlErrorException extends CurlException
{
    /**
     * CurlErrorException constructor.
     *
     * @param int $err
     */
    public function __construct($err)
    {
        parent::__construct(curl_multi_strerror($err));
    }
}