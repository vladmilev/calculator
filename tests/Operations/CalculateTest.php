<?php

namespace Tests\AppBundle\Operations;

use App\Controller\CalculatorController;
use PHPUnit\Framework\TestCase;

class CalculateTest extends TestCase
{
    public function testCalculate()
    {
        $result = CalculatorController::doneCalculation('+', 2, 2);
        $this->assertEquals(4, $result);

        $result = CalculatorController::doneCalculation('*', 2, 2);
        $this->assertEquals(4, $result);

        $result = CalculatorController::doneCalculation('/', 2, 2);
        $this->assertEquals(1, $result);

        $result = CalculatorController::doneCalculation('-', 2, 2);
        $this->assertEquals(0, $result);

        $result = CalculatorController::doneCalculation('/', 2, 0);
        $this->assertEquals("Ошибка деления на ноль", $result);
    }
}
