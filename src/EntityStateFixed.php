<?php

namespace BreakDown\Core\Entity\State;

use RuntimeException;

abstract class EntityStateFixed extends EntityState
{

    public function setCode($code)
    {
        if ($this->getCode()) {
            throw new RuntimeException("Can't move a fixed state to a new value.");
        }

        parent::setCode($code);
    }

}
