<?php namespace Nticaric\Fiskalizacija\Bill;

use XMLWriter;

class PD
{
    public $JirPD;

    public $ZastKodPD;

    public function __construct($JirPD = NULL, $ZastKodPD = NULL)
    {
        $this->JirPD = $JirPD;
        $this->ZastKodPD = $ZastKodPD;
    }

    public function toXML()
    {
        $ns = 'tns';

        $writer = new XMLWriter();
        $writer->openMemory();

        $writer->setIndent(true);
        $writer->setIndentString("    ");
        $writer->startElementNs($ns, 'PrateciDokument', null);

        if ($this->JirPD) {
            $writer->writeElementNs($ns, 'JirPD', null, $this->JirPD);
        } else {
            $writer->writeElementNs($ns, 'ZastKodPD', null, $this->ZastKodPD);
        }
        
        $writer->endElement();

        return $writer->outputMemory();
    }
}
