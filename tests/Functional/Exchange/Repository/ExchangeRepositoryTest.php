<?php

declare(strict_types=1);

namespace App\Tests\Functional\Exchange\Repository;

use App\Exchange\Domain\DTO\ExchangeFilterDTO;
use App\Exchange\Domain\Entity\Exchange;
use App\Exchange\Domain\Repository\ExchangeReadRepositoryInterface;
use App\Exchange\Domain\Repository\ExchangeWriteRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExchangeRepositoryTest extends KernelTestCase
{
    private ExchangeReadRepositoryInterface $readRepository;
    private ExchangeWriteRepositoryInterface $writeRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->readRepository = $container->get(ExchangeReadRepositoryInterface::class);
        $this->writeRepository = $container->get(ExchangeWriteRepositoryInterface::class);
    }

    public function testFindById(): void
    {
        // Предполагается, что в базе данных есть хотя бы одна запись
        $filter = new ExchangeFilterDTO(null, 0, 1);
        $exchanges = $this->readRepository->findByFilter($filter);
        
        if (empty($exchanges)) {
            $this->markTestSkipped('Нет данных для тестирования');
        }
        
        $exchange = $exchanges[0];
        $id = $exchange->getId();
        
        $foundExchange = $this->readRepository->findById($id);
        
        $this->assertNotNull($foundExchange);
        $this->assertEquals($id, $foundExchange->getId());
    }

    public function testFindByFilter(): void
    {
        $filter = new ExchangeFilterDTO(null, 0, 5);
        $exchanges = $this->readRepository->findByFilter($filter);
        
        $this->assertIsArray($exchanges);
        $this->assertLessThanOrEqual(5, count($exchanges));
        
        if (!empty($exchanges)) {
            $this->assertInstanceOf(Exchange::class, $exchanges[0]);
        }
    }

    public function testCountByFilter(): void
    {
        $filter = new ExchangeFilterDTO();
        $count = $this->readRepository->countByFilter($filter);
        
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testSaveAndDelete(): void
    {
        $exchange = new Exchange(
            'Test Exchange',
            true,
            'https://api.test-exchange.com',
            'https://test-exchange.com',
            '/images/exchanges/test.png',
            100,
            '0.001000',
            new \DateTimeImmutable()
        );
        
        $this->writeRepository->save($exchange);
        $this->writeRepository->flush();
        
        $this->assertNotNull($exchange->getId());
        
        $id = $exchange->getId();
        $foundExchange = $this->readRepository->findById($id);
        
        $this->assertNotNull($foundExchange);
        $this->assertEquals('Test Exchange', $foundExchange->getName());
        
        $this->writeRepository->delete($exchange);
        $this->writeRepository->flush();
        
        $deletedExchange = $this->readRepository->findById($id);
        $this->assertNull($deletedExchange);
    }
}