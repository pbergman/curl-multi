<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */
namespace PBergman\CurlMulti;

/**
 * Class Request
 *
 * @package PBergman\CurlMulti
 */
class Request implements RequestInterface
{
    /** @var array  */
    protected $options;

    /**
     * Request constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @{inheritDoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @{inheritDoc}
     */
    public function getOption($opt)
    {
        return isset($this->options[$opt]) ? $this->options[$opt] : null;
    }

    /**
     * @inheritdoc
     */
    public function handle($result, $handle)
    {
        return new Response($this, $result, $handle);
    }
}
