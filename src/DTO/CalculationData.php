<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CalculatorData
{
    /**
     * @Assert\NotBlank(message="Message cannot be empty")
     */
    public $numberOne;

    /**
     * @Assert\NotBlank(message="Message cannot be empty")
     */
    public $operation;

    /**
     * @Assert\NotBlank(message="Message cannot be empty")
     */
    public $numberTwo;

    public $gotoCache = 0;

    public $doneCache = 0;
}
