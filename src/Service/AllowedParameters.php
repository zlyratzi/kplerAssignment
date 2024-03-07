<?php 

namespace App\Service;

class AllowedParameters
{
    public const INVOICE = ['start_date', 'end_date', 'date', 'amount', 'min_amount', 'max_amount', 'is_paid', 'orderBy', 'direction'];
    public const CUSTOMER = ['country', 'currency', 'orderBy', 'direction'];
}