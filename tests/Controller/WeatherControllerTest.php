<?php

namespace App\Tests\Controller;

use App\Controller\WeatherController;
use App\Service\CacheService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WeatherControllerTest extends WebTestCase
{
    public function testWeatherEndpoint(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/weather?city=Sofia');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testWeatherEndpointWithError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/weather?city=');
        $this->assertEquals(['message' => 'The "city" field is required and can\'t be empty.'], json_decode($client->getResponse()->getContent(), true));
        $this->assertResponseStatusCodeSame(422);

        $client->request('GET', '/api/weather?city=Dummy');
        $this->assertEquals(['message' => 'Something went wrong.'], json_decode($client->getResponse()->getContent(), true));
        $this->assertResponseStatusCodeSame(422);
    }

    public function testCityFieldIsRequiredAndCannotBeEmpty()
    {
        $request = new Request(['city' => '']);
        $validator = $this->createMock(ValidatorInterface::class);
        $violations = new ConstraintViolationList([new ConstraintViolation('error', '', [], '', '', '')]);
        $validator->method('validate')->willReturn($violations);

        $controller = new WeatherController($this->createMock(CacheService::class), $validator);
        $response = $controller->getWeather($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(['message' => 'The "city" field is required and can\'t be empty.'], json_decode($response->getContent(), true));
    }

    public function testReturnsWeatherDataForValidCity()
    {
        $request = new Request(['city' => 'London']);
        $validator = $this->createMock(ValidatorInterface::class);
        $violations = new ConstraintViolationList();
        $validator->method('validate')->willReturn($violations);

        $cacheService = $this->createMock(CacheService::class);
        $cacheService->method('getValue')->willReturn(json_encode(['current' => 20, 'lastTenDays' => [15, 18, 20, 22, 19, 21, 23, 20, 18, 17]]));

        $controller = new WeatherController($cacheService, $validator);
        $response = $controller->getWeather($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['city' => 'London', 'temperature' => '20 🥵'], json_decode($response->getContent(), true));
    }

    public function testHandlesExceptionGracefully()
    {
        $request = new Request(['city' => 'London']);
        $validator = $this->createMock(ValidatorInterface::class);
        $violations = new ConstraintViolationList();
        $validator->method('validate')->willReturn($violations);

        $cacheService = $this->createMock(CacheService::class);
        $cacheService->method('getValue')->willThrowException(new \Exception());

        $controller = new WeatherController($cacheService, $validator);
        $response = $controller->getWeather($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(['message' => 'Something went wrong.'], json_decode($response->getContent(), true));
    }
}