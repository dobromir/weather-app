<?php

namespace App\Controller;

use App\Service\CacheService;
use App\Service\TrendTemperatureService;
use App\Service\WeatherApiClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends AbstractController
{
    /**
     *  CacheService instance.
     *.
     * @var CacheService
     */
    private CacheService $cacheService;

    /**
     * ValidatorInterface instance.
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * WeatherController constructor.
     *
     * @param CacheService $cacheService
     * @param ValidatorInterface $validator
     */
    public function __construct(CacheService $cacheService, ValidatorInterface $validator)
    {
        $this->cacheService = $cacheService;
        $this->validator    = $validator;
    }

    /**
    * Show homepage.
    *
    * @return Response
    */
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * Get the temperature for a given city.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getWeather(Request $request): JsonResponse
    {
        $city   = $request->query->get('city');
        $errors = $this->validator->validate($city, [new Assert\NotBlank()]);

        if (count($errors) > 0) {
            $response =  new JsonResponse(['message' => 'The "city" field is required and can\'t be empty.'], 422);
        } else {
            try {
                $weather            = $this->getWeatherData($city);
                $currentTemperature = $weather['current'];
                $averageTemp        = $weather['average'];

                $response = new JsonResponse(
                    [
                        'city'        => $city,
                        'temperature' => $currentTemperature .' ' .  TrendTemperatureService::calculate($currentTemperature, $averageTemp),
                        ]
                );
            } catch (\Exception $error) {
                $response = new JsonResponse(['message' => 'Something went wrong.'], 422);
            }
        }

        return $response;
    }

    /**
     * Get weather data from the API or cache.
     *
     * @param $city
     * @return array
     * @throws \Exception
     */
    private function getWeatherData($city): array
    {
        if (!$this->cacheService->getValue("weather::$city")) {
            $weather = new WeatherApiClient();
            $this->cacheService->setValue("weather::$city", json_encode($weather->fetchTemperature($city)));
        }

        $weather = json_decode($this->cacheService->getValue("weather::$city"), true);

        $currentTemperature = $weather['current'];
        $averageTemp        = round(
            array_sum($weather['lastTenDays']) / count($weather['lastTenDays'])
        );

        return [
            'current' => $currentTemperature,
            'average' => $averageTemp
        ];
    }
}
