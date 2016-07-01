<?php

namespace BreakDown\Core\Entity\State;

use BreakDown\Protocols\Entity\IEntityState;
use BreakDown\Protocols\Entity\IEntityStateNameable;
use ReflectionClass;
use RuntimeException;

abstract class EntityState implements IEntityState, IEntityStateNameable
{

    /**
     *
     * @var mixed
     */
    private $code;

    public function __construct($code = null)
    {
        $this->setCode($code);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {

        $this->code = $code;
        return $this;
    }

    protected function isCode($code)
    {
        return $this->getCode() === $code;
    }

    public function canBeEmpty()
    {
        return true;
    }

    public static function getValidStates()
    {
        $rClass = new ReflectionClass(static::class);
        return $rClass->getConstants();
    }

    public function assert($code)
    {
        $statesList = static::getValidStates();

        if (count($statesList) === 0) {
            throw new RuntimeException("State does not have any options yet.");
        }

        $values = array_values($statesList);

        if (!in_array($code, $values)) {
            throw new RuntimeException("Invalid state value.");
        }
    }

    public function isValid()
    {
        if ($this->getCode()) {
            return true;
        }

        if ($this->canBeEmpty()) {
            return true;
        }

        return false;
    }

    public static function getNameByCode($code)
    {
        if ($code === null) {
            return '';
        }

        $codeNameMap = static::getCodeNameMap();

        if (array_key_exists($code, $codeNameMap)) {
            return $codeNameMap[$code];
        }

        return '';
    }

    public function getName()
    {
        return static::getNameByCode($this->getCode());
    }

    public function getCodeName()
    {
        return $this->getName();
    }

    public static function getStateByCode($code)
    {
        $states = static::getValidStates();
        foreach ($states as $state => $value) {
            if ($code == $value) {
                return $state;
            }
        }
        return null;
    }

    public function getStateSlug()
    {
        return static::getStateByCode($this->getCode());
    }

    public function __toString()
    {
        return trim($this->getCode() . '');
    }

}
