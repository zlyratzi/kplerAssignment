<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CustomerInvoiceService;
use App\Service\InvoicePaymentService;
use App\Service\InvoiceService;
use Symfony\Component\HttpFoundation\Request;

class InvoicesApiController extends AbstractController
{

    private $customerInvoiceService;
    private $invoicePaymentService;
    private $invoiceService;

    public function __construct (
        CustomerInvoiceService $customerInvoiceService, 
        InvoicePaymentService $invoicePaymentService,
        InvoiceService $invoiceService
    ) {        
        $this->customerInvoiceService = $customerInvoiceService;
        $this->invoicePaymentService = $invoicePaymentService;
        $this->invoiceService = $invoiceService;        
    }

    /**
     * @Route("/invoices/{customer_id}", name="get_customer_invoices", methods={"GET"})
     */
    public function getCustomerInvoices($customer_id, Request $request): JsonResponse
    {
        return $this->customerInvoiceService->getCustomerInvoices($customer_id, $request);
    }

    /**
     * @Route("/invoices/{invoice_id}/info", name="get_invoice", methods={"GET"})
     */
    public function getInvoice($invoice_id): JsonResponse
    {
        return $this->invoiceService->getInvoiceService($invoice_id);
    }

    /**
     * @Route("/invoices/{invoice_id}/mark-as-paid", name="update_invoice", methods={"PUT"})
     */
    public function MarkInvoiceAsPaid($invoice_id): JsonResponse
    {
        try {
            return $this->invoicePaymentService->markInvoiceAsPaidService($invoice_id);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @Route("/invoices", name="get_invoices", methods={"GET"})
     */
    public function getInvoices(Request $request): JsonResponse
    {
        return $this->invoiceService->getInvoicesService($request);
    }
}