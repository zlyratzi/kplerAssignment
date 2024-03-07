<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CustomerStatsService;

class CustomersApiController extends AbstractController
{
    private $customerStatsService;

    public function __construct(CustomerStatsService $customerStatsService) 
    {
        $this->customerStatsService = $customerStatsService;
    }

    /**
     * @Route("/customers/{customer_id}/stats", name="get_customer_stats", methods={"GET"})
     */
    public function getCustomerStats($customer_id): JsonResponse
    {
        return $this->customerStatsService->getCustomerStatsService($customer_id);
    }

    /**
     * @Route("/customers", name="get_customers", methods={"GET"})
     */
    public function getCustomers(Request $request): JsonResponse
    {

        return $this->customerStatsService->getCustomersService($request);
       
    }
}
