<?php

namespace Keboola\Xls2CsvProcessor;

class InOut
{

    /**
     * @var string
     */
    private $in;

    /**
     * @var string
     */
    private $out;

    /**
     * InOut constructor.
     *
     * @param string $in
     * @param string $out
     */
    public function __construct(string $in, string $out)
    {
        $this->in = $in;
        $this->out = $out;
    }

    /**
     * @return string
     */
    public function getIn() : string
    {
        return $this->in;
    }

    /**
     * @return string
     */
    public function getOut() : string
    {
        return $this->out;
    }

}