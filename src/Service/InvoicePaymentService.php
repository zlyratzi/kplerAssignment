<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\InvoicesRepository;
use Symfony\Component\HttpFoundation\Response;

class InvoicePaymentService
{

    private $invoicesRepository;

    public function __construct(InvoicesRepository $invoicesRepository)
    {
        $this->invoicesRepository = $invoicesRepository;
    }

    public function markInvoiceAsPaidService($invoice_id): JsonResponse
    {
        try {
            $invoice = $this->findInvoiceById($invoice_id);

            if ($invoice->getIsPaid()) {
                return ErrorHandler::handle(new \Exception('Invoice is already paid'), Response::HTTP_BAD_REQUEST);
            }

            $this->invoicesRepository->markAsPaid($invoice->getId());
            return new JsonResponse(['message' => 'Invoice marked as paid'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    private function findInvoiceById($invoice_id)
    {
        $invoice = $this->invoicesRepository->findOneBy(["invoiceId" => $invoice_id]);
        if (!$invoice) {
            return ErrorHandler::handle(new \Exception('Invoice not found'), Response::HTTP_NOT_FOUND);
        }
        return $invoice;
    }
}
