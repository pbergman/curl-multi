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
        $timeout = array_fill(0, $this->max, microtime(true) - $this->per);

        while (false === $this->queue->isEmpty()) {
            $total = count($this->queue);
            $now = microtime(true);
            $free = [];
            $sleep = null;

            foreach ($timeout as $i => $wait) {
                if ($wait < $now) {
                    $free[] = $i;
                    $total--;
                }
                if (0 === $total) {
                    break;
                }
            }

            foreach ($free as $index) {
                parent::add($this->queue->dequeue());
            }

            foreach ($timeout as $i => &$wait) {
                if (in_array($i, $free)) {
                    $wait = $now;
                } else {
                    $sleep = $wait;
                }
            }

            if (count($free) > 0) {
                foreach(parent::getResponse() as $response) {
                    yield $response;
                }
            }

            if ($total > 0 && microtime(true) > $sleep) {
                usleep((int)(microtime(true)-$sleep)*1000000);
            }
        }
    }
}