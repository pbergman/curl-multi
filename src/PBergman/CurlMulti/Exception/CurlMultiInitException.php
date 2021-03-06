<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
declare(strict_types=1);

namespace PBergman\CurlMulti\Exception;

class CurlMultiInitException extends CurlException
{
    public function __construct()
    {
        parent::__construct("failed to initialize the curl multi handler");
    }
}
