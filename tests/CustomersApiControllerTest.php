<?php

namespace Tests\Controller;

use App\Controller\CustomersApiController;
use App\Service\CustomerStatsService;
use App\Service\SearchCriteriaHandler;
use App\Repository\CustomersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class CustomersApiControllerTest extends TestCase
{
    private $customerStatsService;
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock ManagerRegistry
        $managerRegistryMock = $this->createMock(ManagerRegistry::class);

        // Mock CustomersRepository (replace CustomersRepository with your actual repository class)
        $customersRepositoryMock = $this->createMock(CustomersRepository::class);

        // Mock SearchCriteriaHandler (if needed)
        $searchCriteriaHandlerMock = $this->createMock(SearchCriteriaHandler::class);
    
        // Instantiate the CustomerStatsService with the mocks
        $this->customerStatsService = new CustomerStatsService($customersRepositoryMock, $searchCriteriaHandlerMock);

        // Create a real instance of the controller with the mocked service
        $this->controller = new CustomersApiController($this->customerStatsService);
    }

    public function testGetCustomerStats(): void
    {
        $customerId = "2c92a00766e7b2210166ea9292423071";

        // Call the controller method
        $response = $this->controller->getCustomerStats($customerId);

        // Assert that the response is an instance of JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);
        // Optionally, you can assert the response content or status code if needed
    }

    public function testGetCustomers(): void
    {
        $request = new Request();

      
        // Call the controller method
        $response = $this->controller->getCustomers($request);

        // Assert that the response is an instance of JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);
        // Optionally, you can assert the response content or status code if needed
    }
}
