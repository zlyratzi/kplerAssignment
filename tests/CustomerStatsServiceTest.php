<?php

namespace Tests\Service;

use App\Repository\CustomersRepository;
use App\Service\CustomerStatsService;
use App\Service\SearchCriteriaHandler;
use PHPUnit\Framework\TestCase;
use App\Entity\Customers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CustomerStatsServiceTest extends TestCase
{
    private $customersRepositoryMock;
    private $searchCriteriaHandlerMock;

    protected function setUp(): void
    {
        parent::setUp();
        // Create mock objects for dependencies
        $this->customersRepositoryMock = $this->createMock(CustomersRepository::class);
        $this->searchCriteriaHandlerMock = $this->createMock(SearchCriteriaHandler::class);
    }

    public function testGetCustomerStatsService(): void
    {
        $customerId = "1";
        $customerData = [
            'id' => 20,
            'identifier' => '54016711a9a81164ce',
            'country' => 'USA',
            'currency' => 'USD'
        ];
        
        $customerEntity = $this->getMockBuilder(Customers::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerEntity->method('getId')->willReturn($customerData['id']);
        $customerEntity->method('getIdentifier')->willReturn($customerData['identifier']);
        $customerEntity->method('getCountry')->willReturn($customerData['country']);
        $customerEntity->method('getCurrency')->willReturn($customerData['currency']);

        $this->customersRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($customerEntity);

        $service = new CustomerStatsService($this->customersRepositoryMock, $this->searchCriteriaHandlerMock);
        $response = $service->getCustomerStatsService($customerId);

        $expectedResponse = new JsonResponse($customerData);
        $this->assertEquals($expectedResponse->getContent(), $response->getContent());
    }

}
