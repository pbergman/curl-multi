<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti\Exception;

/**
 * Class CurlInitException
 *
 * @package PBergman\CurlMulti\Exception
 */
class CurlInitException extends CurlException
{
    /**
     * CurlInitException constructor.
     */
    public function __construct()
    {
        parent::__construct("failed to initialize the curl handler");
    }
}
