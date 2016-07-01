<?php

namespace BreakDown\Core\Entity\State;

use BreakDown\Protocols\Entity\IEntityStateTransactional;
use RuntimeException;

abstract class EntityStateTransactional extends EntityState implements IEntityStateTransactional
{

    /**
     *
     * @return EntityStateTransitMap
     */
    static protected function getStateTransitMap()
    {
        return null;
    }

    protected function canTransitTo($nextCode)
    {
        $stateTransitMap = static::getStateTransitMap();

        if (!$stateTransitMap) {
            throw new RuntimeException("Unable to infer transitable paths for transactional state.");
        }

        return $stateTransitMap->hasAcceptableTransitFor($this->getCode(), $nextCode);
    }

    public function setCode($code)
    {
        if (!$this->canTransitTo($code)) {
            throw new RuntimeException("Cannot change transactional state to the given new value.");
        }

        parent::setCode($code);
    }

}
