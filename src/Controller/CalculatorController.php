<?php

namespace App\Controller;

use App\DTO\CalculatorData;
use App\Form\CalculatorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CalculatorController extends AbstractController
{
    #[Route('/', name: 'app_calculator')]
    public function index(Request $request, EntityManagerInterface $em, AdapterInterface $cache): Response
    {
        $form = $this->createForm(CalculatorType::class, new CalculatorData());
        //$cache->clear();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var CalculatorData $data */
            $data = $form->getData();

            $result = "";
            $countCache= 0;
            $cacheResult= "";
            do {
                $found = false;
                if ($cache->hasItem('calculator_pool_' . $countCache )) {
                    $found = true;
                    $cacheResult .= $this->getFromCache($cache, null, $countCache) . "<br>";
                    $countCache++;
                }
            } while ($found);
//var_dump($countCache);
            if ($cacheResult)
                $cacheResult = "<hr>".$cacheResult;

            // Cache
            if ($data->gotoCache || $data->doneCache) {
                if ($data->gotoCache) {
//var_dump($countCache);
                    $result = $this->getFromCache($cache, $data, $countCache);
                }
                if ($data->doneCache) {
                    $cacheResult= "";
                    do {
                        $found = false;
                        if ($cache->hasItem('calculator_pool_' . $countCache-1 )) {
                            $found = true;
                            $cacheResult .= $this->getFromCache($cache, null, $countCache-1, true) . "<br>";
                            $cache->deleteItem('calculator_pool_' . $countCache );
                            $countCache--;
                        }
                    } while ($found);
                    $cache->clear();
                }
            } else {
                // Run!
                try {
                    $result = self::doneCalculation($data->operation, $data->numberOne, $data->numberTwo);
                } catch(\Exception $e) {
                    $result= "Произошла ошибка, обратитесь к разработчику";
                }
                $result = "$data->numberOne $data->operation $data->numberTwo = $result";
            }
//var_dump($result. $cacheResult);

            return $this->render('calculator/index.html.twig', [
                    'form' => $form->createView(),
                    'result' => $result . $cacheResult,
                ]);
        }

        return $this->render('calculator/index.html.twig', [
            'form' => $form->createView(),
            'result' => null,
        ]);
    }

    public static function doneCalculation($operation, $num1, $num2) {
        switch ($operation) {
            case '+':
                return $result = $num1 + $num2;
            case '-':
                return $result= $num1 - $num2;
            case '*':
                return $result= $num1 * $num2;
            case '/':
                return ($num2 !== 0) ? $result= $num1 / $num2: "Ошибка деления на ноль";
        }
        return null;
    }

    private function getFromCache(AdapterInterface $cache, $data, $cacheNumber, $decide = false) {
        $json = $cache->get(
            'calculator_pool_' . $cacheNumber,
            function () use ($data) {
                return json_encode([
                    'numberOne' => $data->numberOne,
                    'operation' => $data->operation,
                    'numberTwo' => $data->numberTwo,
                ]);
            }
        );
        $item = json_decode($json, true);
        if ($decide) {
            $result = self::doneCalculation($item['operation'], $item['numberOne'], $item['numberTwo']);
            return $item['numberOne'] . ' ' . $item['operation'] . ' ' . $item['numberTwo'] . ' = ' . $result;
        }
        return $item['numberOne'] .' '. $item['operation'] .' ' . $item['numberTwo'] . ' - (в очереди)';
    }

}
