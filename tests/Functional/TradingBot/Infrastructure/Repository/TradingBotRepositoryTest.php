<?php

declare(strict_types=1);

namespace App\Tests\Functional\TradingBot\Infrastructure\Repository;

use App\TradingBot\Domain\Entity\TradingBot;
use App\TradingBot\Domain\Params\GetAllParams;
use App\TradingBot\Infrastructure\Repository\TradingBotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Functional test for TradingBotRepository
 * 
 * This test verifies that the TradingBotRepository correctly:
 * - Saves TradingBot entities to the database
 * - Retrieves TradingBot entities with and without filters
 * 
 * Run with: php bin/phpunit tests/Functional/TradingBot/Infrastructure/Repository/TradingBotRepositoryTest.php
 */

class TradingBotRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private TradingBotRepository $repository;

    /**
     * Set up the test environment before each test
     * - Boot the Symfony kernel
     * - Get the entity manager and repository from the container
     * - Clear any existing TradingBot entities from the database
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->repository = $container->get(TradingBotRepository::class);
        
        // Clear the database before each test
        $this->entityManager->createQuery('DELETE FROM App\TradingBot\Domain\Entity\TradingBot')->execute();
    }

    /**
     * Test that the save method correctly persists a TradingBot entity
     * and that we can retrieve it afterward with the correct values.
     */
    public function testSave(): void
    {
        // Create a new TradingBot entity
        $tradingBot = new TradingBot(
            'Test Bot',
            1, // exchangeId
            2, // tradingPairBaseId
            3, // tradingPairQuoteId
            '1000', // rangePriceFrom
            '2000', // rangePriceTo
            10, // gridCount
            '100' // step
        );

        // Save the entity
        $result = $this->repository->save($tradingBot);

        // Assert that save was successful
        $this->assertTrue($result);
        $this->assertNotNull($tradingBot->getId());

        // Verify the entity was saved by retrieving it
        $params = new GetAllParams($tradingBot->getId());
        $retrievedBots = $this->repository->getAll($params);
        
        $this->assertCount(1, $retrievedBots);
        $retrievedBot = $retrievedBots[0];
        
        $this->assertEquals('Test Bot', $retrievedBot->getBotName());
        $this->assertEquals(1, $retrievedBot->getExchangeId());
        $this->assertEquals(2, $retrievedBot->getTradingPairBaseId());
        $this->assertEquals(3, $retrievedBot->getTradingPairQuoteId());
        $this->assertEquals('1000', $retrievedBot->getRangePriceFrom());
        $this->assertEquals('2000', $retrievedBot->getRangePriceTo());
        $this->assertEquals(10, $retrievedBot->getGridCount());
        $this->assertEquals('100', $retrievedBot->getStep());
    }

    /**
     * Test that the getAll method correctly retrieves TradingBot entities
     * with and without filters applied.
     * This test verifies:
     * - Filtering by ID returns only the matching entity
     * - Getting all entities without filters returns all entities
     */
    public function testGetAllWithFilter(): void
    {
        // Create multiple TradingBot entities
        $tradingBot1 = new TradingBot(
            'Bot 1',
            1,
            2,
            3,
            '1000',
            '2000',
            10,
            '100'
        );
        
        $tradingBot2 = new TradingBot(
            'Bot 2',
            2,
            3,
            4,
            '2000',
            '3000',
            15,
            '200'
        );

        // Save the entities
        $this->repository->save($tradingBot1);
        $this->repository->save($tradingBot2);

        // Test filtering by ID
        $params = new GetAllParams($tradingBot1->getId());
        $result = $this->repository->getAll($params);
        
        $this->assertCount(1, $result);
        $this->assertEquals('Bot 1', $result[0]->getBotName());

        // Test getting all bots
        $params = new GetAllParams();
        $result = $this->repository->getAll($params);
        
        $this->assertCount(2, $result);
    }

    /**
     * Clean up after each test
     * - Remove any TradingBot entities created during the test
     * - Close the entity manager to avoid memory leaks
     */
    protected function tearDown(): void
    {
        // Clean up the database after tests
        $this->entityManager->createQuery('DELETE FROM App\TradingBot\Domain\Entity\TradingBot')->execute();
        
        parent::tearDown();
        
        // Avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}