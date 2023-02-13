<?php namespace Nticaric\Fiskalizacija\Bill;

use XMLWriter;

class BillPD extends Bill
{
    public $prateciDokument;


    public function setPrateciDokument($prateciDokument)
    {
        $this->prateciDokument = $prateciDokument;
    }

    public function toXML()
    {
        $ns = 'tns';

        $writer = new XMLWriter();
        $writer->openMemory();

        $writer->setIndent(true);
        $writer->setIndentString("    ");
        $writer->startElementNs($ns, 'Racun', null);
        $writer->writeElementNs($ns, 'Oib', null, $this->oib);
        $writer->writeElementNs($ns, 'USustPdv', null, $this->havePDV ? "true" : "false");
        $writer->writeElementNs($ns, 'DatVrijeme', null, $this->dateTime);
        $writer->writeElementNs($ns, 'OznSlijed', null, $this->noteOfOrder);

        $writer->writeRaw($this->billNumber->toXML());

        /*********** PDV *****************************/
        if (!empty($this->listPDV)) {
            $writer->startElementNs($ns, 'Pdv', null);
            foreach ($this->listPDV as $pdv) {
                $writer->writeRaw($pdv->toXML());
            }
            $writer->endElement();
        }
        /*********************************************/

        /*********** PNP *****************************/
        if (!empty($this->listPNP)) {
            $writer->startElementNs($ns, 'Pnp', null);
            foreach ($this->listPNP as $pnp) {
                $writer->writeRaw($pnp->toXML());
            }
            $writer->endElement();
        }
        /*********************************************/

        /*********** Ostali Porez ********************/
        if (!empty($this->listOtherTaxRate)) {
            $writer->startElementNs($ns, 'OstaliPor', null);
            foreach ($this->listOtherTaxRate as $ostali) {
                $writer->writeRaw($ostali->toXML());
            }
            $writer->endElement();
        }
        /*********************************************/

        $writer->writeElementNs($ns, 'IznosOslobPdv', null, number_format($this->taxFreeValuePdv, 2, '.', ''));
        $writer->writeElementNs($ns, 'IznosMarza', null, number_format($this->marginForTaxRate, 2, '.', ''));
        $writer->writeElementNs($ns, 'IznosNePodlOpor', null, number_format($this->taxFreeValue, 2, '.', ''));

        /*********** Naknada *************************/
        if (!empty($this->refund)) {
            $writer->startElementNs($ns, 'Naknade', null);
            foreach ($this->refund as $naknada) {
                $writer->writeRaw($naknada->toXML());
            }
            $writer->endElement();
        }
        /*********************************************/

        $writer->writeElementNs($ns, 'IznosUkupno', null, number_format($this->totalValue, 2, '.', ''));
        $writer->writeElementNs($ns, 'NacinPlac', null, $this->typeOfPaying);
        $writer->writeElementNs($ns, 'OibOper', null, $this->oibOperative);
        $writer->writeElementNs($ns, 'ZastKod', null, $this->securityCode);
        $writer->writeElementNs($ns, 'NakDost', null, $this->noteOfRedelivary ? "true" : "false");

        if ($this->noteOfParagonBill) {
            $writer->writeElementNs($ns, 'ParagonBrRac', null, $this->noteOfParagonBill);
        }

        if ($this->specificPurpose) {
            $writer->writeElementNs($ns, 'SpecNamj', null, $this->specificPurpose);
        }

        $writer->writeRaw($this->prateciDokument->toXML());

        $writer->endElement();

        return $writer->outputMemory();
    }

}
