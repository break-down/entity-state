<?php

namespace BreakDown\Core\Entity\State;

use RuntimeException;

class EntityStateTransitMap
{

    /**
     *
     * @var array
     */
    protected $list = [];

    public function addTransit($initialCode)
    {
        $transit = new EntityStateTransit($initialCode);
        $this->list[] = $transit;

        return $transit;
    }

    public function isValid()
    {
        return count($this->list) > 0;
    }

    /**
     *
     * @param mixed $code
     * @param mixed $codeTo
     * @return boolean
     */
    public function hasAcceptableTransitFor($code, $codeTo)
    {
        if (!$this->isValid()) {
            throw new RuntimeException("Invalid state transit map found.");
        }

        $transitTo = null;
        $transitFrom = null;

        foreach ($this->list as $transit) {
            /* @var $transit EntityStateTransit */
            if ($transitTo === null) {
                if ($transit->appliesToState($code)) {
                    $transitTo = $transit;
                }
            }

            if ($transitFrom === null) {
                if ($transit->appliesToState($codeTo)) {
                    $transitFrom = $transit;
                }
            }
        }

        if ($transitTo && $transitTo->canReachTo($code, $codeTo)) {
            return true;
        }

        if ($transitFrom && $transitFrom->canBeReachedFrom($codeTo, $code)) {
            return true;
        }

        return false;
    }

}
