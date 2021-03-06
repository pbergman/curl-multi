<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
declare(strict_types=1);

namespace PBergman\CurlMulti;

use PBergman\CurlMulti\Exception;

class MultiHandler implements MultiHandlerInterface
{
    /** @var resource  */
    private $handle;
    /** @var array  */
    private $requests = [];

    /**
     * @param RequestInterface[] $request
     *
     * @throws Exception\CurlErrorException
     * @throws Exception\CurlInitException
     * @throws Exception\CurlMultiInitException
     * @throws Exception\CurlSetOptException
     */
    public function __construct(RequestInterface ...$request)
    {
        $this->init();

        foreach ($request as $r) {
            $this->add($r);
        }
    }

    public function add(RequestInterface $request) :MultiHandlerInterface
    {
        if (false === ($handle = curl_init())) {
            throw new Exception\CurlInitException();
        }

        if (!curl_setopt_array($handle, $request->getOptions())) {
            throw new Exception\CurlSetOptException($request);
        }

        if (CURLM_OK !== $status = curl_multi_add_handle($this->handle, $handle)) {
            throw new Exception\CurlErrorException($status);
        }

        $this->requests[] = [$handle, $request, true];

        return $this;
    }

    public function init() :void
    {
        if (null === $this->handle && false === ($this->handle = curl_multi_init())) {
            throw new Exception\CurlMultiInitException();
        }
    }

    public function close() :void
    {
        curl_multi_close($this->handle);
        $this->handle = null;
        foreach (array_column($this->requests, 0) as $handle) {
            curl_close($handle);
        }
        $this->requests = [];
    }

    public function getResponse() :\Generator
    {
        if (null === $this->handle) {
            throw new Exception\CurlException('the curl multi handler is not initialized');
        }

        do {
            // according to the libcurl documentation, you should
            // add your own sleep if curl_multi_select returns -1
            // and proceed.
            if (-1 === curl_multi_select($this->handle)) {
                usleep(10);
            }
            if (CURLE_OK == $status = curl_multi_exec($this->handle, $active)) {
                while (($info = curl_multi_info_read($this->handle)) && CURLMSG_DONE === $info['msg']) {
                    curl_multi_remove_handle($this->handle, $info['handle']);

                    if (false === $index = array_search($info['handle'], array_column($this->requests, 0), true)) {
                        throw new Exception\CurlException('Unexpected handler ' . $info['handle']);
                    }

                    $this->requests[$index][2] = false;

                    yield $this->requests[$index][1]->handle($info['result'], $info['handle']);
                }
            } elseif (CURLM_CALL_MULTI_PERFORM !== $status) {
                throw new Exception\CurlErrorException($status);
            }
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);
    }

    public function wait() :array
    {
        return iterator_to_array($this->getResponse());
    }

    public function setOptions(array $options) :bool
    {
        foreach ($options as $key => $value) {
            if (false === $this->setOption($key, $value)) {
                return false;
            }
        }
        return true;
    }

    public function free(): void
    {
        $requests = [];

        foreach ($this->requests as [$handler, $request, $status]) {
            if ($status) {
                $requests[] = [$handler, $request, $status];
            } else {
                if (is_resource($handler)) {
                    curl_close($handler);
                }
            }
        }

        $this->requests = $requests;
    }

    public function setOption(int $key, $value) :bool
    {
        return curl_multi_setopt($this->handle , $key , $value);
    }

    protected function getRequestIndex($handle) :int
    {
        if (false !== $index = array_search($handle, array_column($this->requests, 0), true)) {
            return $index;
        }

        return -1;
    }
}