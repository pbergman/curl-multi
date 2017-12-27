<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti\Exception;

use PBergman\CurlMulti\RequestInterface;

/**
 * Class CurlSetOptException
 *
 * @package PBergman\CurlMulti\Exception
 */
class CurlSetOptException extends CurlException
{
    /** @var RequestInterface  */
    protected $request;

    /**
     * CurlSetOptException constructor.
     *
     * @param RequestInterface $r
     */
    public function __construct(RequestInterface $r)
    {
        $this->request = $r;
        parent::__construct('failed to set options to curl handler');
    }
}