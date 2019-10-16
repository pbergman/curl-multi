<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
declare(strict_types=1);

namespace PBergman\CurlMulti\Exception;

use PBergman\CurlMulti\RequestInterface;

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