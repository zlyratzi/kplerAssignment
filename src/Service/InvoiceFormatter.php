<?php

namespace App\Service;

use App\Entity\Invoices;

class InvoiceFormatter
{
    public static function formatInvoiceData(Invoices $invoice): array
    {
        return [
            'id' => $invoice->getId(),
            'invoice_id' => $invoice->getInvoiceId(),
            'amount' => $invoice->getAmount(),
            'invoice_date' => $invoice->getInvoiceDate()->format('d-m-Y'),
            'is_paid' => $invoice->getIsPaid(),
        ];
    }
}
