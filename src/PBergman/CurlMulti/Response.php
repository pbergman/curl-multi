<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti;

/**
 * Class Response
 *
 * @package PBergman\CurlMulti
 */
class Response implements ResponseInterface
{
    /** @var RequestInterface  */
    protected $request;
    /** @var int */
    protected $result;
    /** @var resource  */
    protected $handle;

    /**
     * Response constructor.
     *
     * @param RequestInterface $request
     * @param int $result
     * @param resource $handle
     */
    public function __construct(RequestInterface $request, $result, $handle)
    {
        $this->request = $request;
        $this->result = $result;
        $this->handle = $handle;
    }

    /**
     * @inheritdoc
     */
    public function getInfo($opt = null)
    {
        return (is_null($opt)) ? curl_getinfo($this->handle) : curl_getinfo($this->handle, $opt);
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return curl_multi_getcontent($this->handle);
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return curl_multi_strerror($this->result);
    }

    /**
     * @inheritdoc
     */
    public function hasError()
    {
        return CURLE_OK === $this->result;
    }

    /**
     * @inheritdoc
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @inheritdoc
     */
    public function getRequest()
    {
        return $this->request;
    }
}