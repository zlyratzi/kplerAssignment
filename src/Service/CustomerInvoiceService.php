<?php

namespace App\Service;

use App\Repository\CustomersRepository;
use App\Repository\InvoicesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerInvoiceService
{


    private $customersRepository;
    private $invoicesRepository;
    private $searchCriteriaHandler;

    public function __construct(
        CustomersRepository $customersRepository,
        InvoicesRepository $invoicesRepository,
        SearchCriteriaHandler $searchCriteriaHandler
    ) {
        $this->customersRepository = $customersRepository;
        $this->invoicesRepository = $invoicesRepository;
        $this->searchCriteriaHandler = $searchCriteriaHandler;
    }

    public function getCustomerInvoices(string $customerId, Request $request): JsonResponse
    {
        try {
            $customer = $this->findCustomerByIdentifier($customerId);
            $criteria = $this->extractSearchCriteria($request);
            $invoices = $this->invoicesRepository->findInvoicesByCustomer($customer->getId(), $criteria);
            $invoicesData = $this->formatInvoicesData($invoices);
            return new JsonResponse($invoicesData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function findCustomerByIdentifier(string $customerId)
    {
        $customer = $this->customersRepository->findOneBy(["identifier" => $customerId]);
        if (!$customer) {
            throw new \Exception('Undefined customer identifier: ' . $customerId, Response::HTTP_NOT_FOUND);
        }
        return $customer;
    }

    private function extractSearchCriteria(Request $request): array
    {
        $requestParameters = $request->query->all();
        if (!empty($requestParameters)) {
            $searchResult = $this->searchCriteriaHandler->getSearchCriteria($request, "INVOICE");
            if (isset($searchResult['error'])) {
                throw new \Exception($searchResult['error'], $searchResult['status']);
            }
            return $searchResult['criteria'];
        }
        return [];
    }

    private function formatInvoicesData($invoices)
    {
        $invoicesArray = [];
        foreach ($invoices as $invoice) {
            $invoicesArray[] = InvoiceFormatter::formatInvoiceData($invoice);
        }
        return $invoicesArray;
    }
}
