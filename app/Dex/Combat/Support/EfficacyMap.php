<?php namespace BetterDex\Dex\Combat\Support;

class EfficacyMap
{
    /**
     * @var array
     */
    private $data;

    public function __construct(Array $data)
    {
        $this->data = $data;
    }
}