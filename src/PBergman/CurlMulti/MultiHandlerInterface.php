<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
declare(strict_types=1);

namespace PBergman\CurlMulti;

interface MultiHandlerInterface
{
    /**
     * @param RequestInterface $request
     *
     * @throws Exception\CurlErrorException
     * @throws Exception\CurlInitException
     * @throws Exception\CurlSetOptException
     *
     * @return $this
     */
    public function add(RequestInterface $request) :MultiHandlerInterface;

    /**
     * @throws Exception\CurlMultiInitException
     */
    public function init() :void;

    /**
     * Will close the curl multi handler and
     * all active curl handlers. Best to use
     * in defer pattern:
     *
     * try {
     *   $handler = new MultiHandler();
     *   ....
     * } finally {
     *   $handler->close();
     * }
     *
     */
    public function close() :void;

    /**
     * Will process all registered request and
     * yield the response when one is available.
     *
     * The response returned could be in differ
     * order than that the were registered
     * because of the asynchronously execution
     * of the curl multi handler
     *
     * @return \Generator|ResponseInterface[]
     * @throws Exception\CurlErrorException
     * @throws Exception\CurlException
     */
    public function getResponse() :\Generator;

    /**
     * similar is the getResponse method but
     * will wait till everything is finished
     * and return an array of responses
     *
     * @return array|ResponseInterface[]
     * @throws Exception\CurlErrorException
     * @throws Exception\CurlException
     */
    public function wait() :array;

    /**
     * set multiple options for the curl mutli handler
     *
     * @param array $options
     * @return bool
     */
    public function setOptions(array $options) :bool;

    /**
     * set option for the curl mutli handler
     *
     * @param int $key
     * @param mixed $value
     * @return bool
     */
    public function setOption(int $key, $value) :bool;

    /**
     * close all finished curl handlers
     */
    public function free(): void;
}