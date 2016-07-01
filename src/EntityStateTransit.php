<?php

namespace BreakDown\Core\Entity\State;

class EntityStateTransit
{

    /**
     *
     * @var mixed
     */
    protected $initialState;

    /**
     *
     * @var array
     */
    private $statesTo;

    /**
     *
     * @var boolean
     */
    private $canReachToAll = false;

    /**
     *
     * @var array
     */
    private $statesFrom;

    /**
     *
     * @var boolean
     */
    private $canBeReachedFromAll = false;

    public function __construct($initialState)
    {
        $this->initialState = $initialState;
        $this->statesTo = [];
        $this->statesFrom = [];
    }

    public function toAll()
    {
        $this->canReachToAll = true;
        $this->statesTo = [];

        return $this;
    }

    public function to($code)
    {
        if (!in_array($code, $this->statesTo)) {
            $this->statesTo[] = $code;
            $this->canReachToAll = false;
        }

        return $this;
    }

    public function fromAll()
    {
        $this->canBeReachedFromAll = true;
        $this->statesFrom = [];

        return $this;
    }

    public function from($code)
    {
        if (!in_array($code, $this->statesFrom)) {
            $this->statesFrom[] = $code;
            $this->canBeReachedFromAll = false;
        }

        return $this;
    }

    public function appliesToState($code)
    {
        return $this->initialState === $code;
    }

    public function canReachTo($code, $codeTo)
    {
        if (!$this->appliesToState($code)) {
            return false;
        }

        if ($this->canReachToAll === true) {
            return true;
        }

        if (is_array($this->statesTo)) {

            if (count($this->statesTo) === 0) {
                return false;
            }

            if (in_array($codeTo, $this->statesTo)) {
                return true;
            }
        }

        return false;
    }

    public function canBeReachedFrom($code, $codeFrom)
    {
        if (!$this->appliesToState($code)) {
            return false;
        }

        if ($this->canBeReachedFromAll === true) {
            return true;
        }

        if (is_array($this->statesFrom)) {

            if (count($this->statesFrom) === 0) {
                return false;
            }

            if (in_array($codeFrom, $this->statesFrom)) {
                return true;
            }
        }

        return false;
    }

}
