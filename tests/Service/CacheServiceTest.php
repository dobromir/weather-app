<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\CacheService;
use Predis\ClientInterface;

class CacheServiceTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->expects($this->any())
            ->method('__call')
            ->willReturnCallback(function ($command, $args) {
                return match ($command) {
                    'set'   => 'OK',
                    'get'   => 'testValue',
                    default => true,
                };
            });

        $cacheService = new CacheService($mockClient);
        $cacheService->setValue('testKey', 'testValue');

        $value = $cacheService->getValue('testKey');
        $this->assertSame('testValue', $value);
    }
}
