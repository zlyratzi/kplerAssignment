<?php

namespace App\Service;

use App\Repository\CustomersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerStatsService
{

    private $customersRepository;
    private $searchCriteriaHandler;

    public function __construct(CustomersRepository $customersRepository, SearchCriteriaHandler $searchCriteriaHandler)
    {
        $this->customersRepository = $customersRepository;
        $this->searchCriteriaHandler = $searchCriteriaHandler;
    }

    public function getCustomerStatsService(string $customerId): JsonResponse
    {
        try {
            $customer = $this->findCustomerByIdentifier($customerId);

            $customerData = $this->formatCustomerData($customer);
            return new JsonResponse($customerData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getCustomersService(Request $request): JsonResponse
    {
        try {
            $customers = $this->fetchCustomers($request);

            $customersData = $this->formatCustomersData($customers);

            return new JsonResponse($customersData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function findCustomerByIdentifier(string $customerId)
    {
        $customer = $this->customersRepository->findOneBy(["identifier" => $customerId]);
        if (!$customer) {
            throw new \Exception('Customer not found', Response::HTTP_NOT_FOUND);
        }
        return $customer;
    }

    private function fetchCustomers(Request $request)
    {
        $customers = $this->customersRepository->findAll();
        $requestParameters = $request->query->all();
        if (!empty($requestParameters)) {
            $searchResult = $this->searchCriteriaHandler->getSearchCriteria($request, "CUSTOMER");
            if (isset($searchResult['error'])) {
                throw new \Exception($searchResult['error'], $searchResult['status']);
            }
            $criteria = $searchResult['criteria'];
            $customers = $this->customersRepository->findCustomersByCustomCriteria($criteria);
        }
        return $customers;
    }

    private function formatCustomerData($customer)
    {
        return [
            'id' => $customer->getId(),
            'identifier' => $customer->getIdentifier(),
            'country' => $customer->getCountry(),
            'currency' => $customer->getCurrency(),
        ];
    }

    private function formatCustomersData($customers)
    {
        $customersArray = [];
        if (!is_null($customers)) {
            foreach ($customers as $customer) {
                $customersArray[] = $this->formatCustomerData($customer);
            }
        }
        return $customersArray;
    }

}
