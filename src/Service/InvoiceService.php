<?php

namespace App\Service;

use App\Repository\InvoicesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceService
{
    private $invoicesRepository;
    private $searchCriteriaHandler;

    public function __construct(InvoicesRepository $invoicesRepository, SearchCriteriaHandler $searchCriteriaHandler)
    {
        $this->invoicesRepository = $invoicesRepository;
        $this->searchCriteriaHandler = $searchCriteriaHandler;
    }


    public function getInvoicesService(Request $request): JsonResponse
    {
        try {
            $invoices = $this->getAllInvoices();
            $invoices = $this->applySearchCriteria($request, $invoices);
            $invoicesData = $this->formatInvoicesData($invoices);
            return new JsonResponse($invoicesData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function getInvoiceService(string $invoiceId): JsonResponse
    {
        try {
            $invoice = $this->invoicesRepository->findOneBy(["invoiceId" => $invoiceId]);
            if (!$invoice) {
                return ErrorHandler::handle(new \Exception('Invoice not found'), Response::HTTP_NOT_FOUND);
            }
            $invoiceData = InvoiceFormatter::formatInvoiceData($invoice);
            return new JsonResponse($invoiceData, Response::HTTP_OK);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function getAllInvoices(): array
    {
        $invoices = $this->invoicesRepository->findAll();
        if ($invoices === null) {
            return []; // Return an empty array if no invoices are found
        }
        return $invoices;
    }

    private function applySearchCriteria(Request $request, array $invoices): array
    {
        $requestParameters = $request->query->all();
        if (!empty($requestParameters) && !is_null($requestParameters)) {           
            $searchResult = $this->searchCriteriaHandler->getSearchCriteria($request, "INVOICE");
            if (isset($searchResult['error'])) {
                throw new \Exception($searchResult['error'], $searchResult['status']);
            }
            $criteria = $searchResult['criteria'];
            return $this->invoicesRepository->findInvoicesByCustomCriteria($criteria);
        }
        return $invoices;
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
