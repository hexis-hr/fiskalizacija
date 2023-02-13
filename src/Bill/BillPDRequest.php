<?php namespace Nticaric\Fiskalizacija\Bill;

use Nticaric\Fiskalizacija\Request;

class BillPDRequest extends Request
{ 
    public function __construct(Bill $bill)
    {
        $this->request     = $bill;
        $this->requestName = 'RacunPDZahtjev';
    }
}