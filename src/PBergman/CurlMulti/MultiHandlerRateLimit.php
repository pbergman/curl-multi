<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
declare(strict_types=1);

namespace PBergman\CurlMulti;

class MultiHandlerRateLimit extends MultiHandler
{
    private $max;
    private $per;
    private $queue;

    public function __construct(int $max, int $per)
    {
        parent::__construct();

        $this->max = $max;
        $this->per = $per;
        $this->queue = new \SplQueue();

        $this->setOption(CURLMOPT_MAXCONNECTS, $max);
    }

    public function add(RequestInterface $request): MultiHandlerInterface
    {
        $this->queue->enqueue($request);
        return $this;
    }

    public function getResponse(): \Generator
    {
        $ref = array_fill(0, $this->max, microtime(true) - $this->per);
        while (false === $this->queue->isEmpty()) {
            $sleep = null;
            $now = microtime(true);
            $process = false;
            foreach ($ref as $i => $wait) {
                if ($wait < $now) {
                    parent::add($this->queue->dequeue());
                    $ref[$i] = microtime(true) + $this->per;
                    $process = true;
                    if (null === $sleep) {
                        $sleep = $ref[$i];
                    }
                } else {
                    if (null === $sleep || $sleep > $wait) {
                        $sleep = $wait;
                    }
                }
            }
            if ($process) {
                foreach(parent::getResponse() as $response) {
                    yield $response;
                }
            }
            if (microtime(true) > $sleep) {
                usleep((microtime(true)-$sleep)*1000000);
            }
        }
    }
}