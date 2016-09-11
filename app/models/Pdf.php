<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 10.09.2016
 * Time: 22:09
 */

namespace Vokuro\Models;

use Vokuro\fpdf\fpdf;


class Pdf extends fpdf
{
    // Simple table
    function BasicTable($header, $data)
    {
        //Fett
        $this->SetFont('','B');
        // Header
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();

        //nicht mehr Fett
        $this->SetFont('');
        // Data
        foreach($data as $row)
        {
            $this->Cell(40, 6, '', 1);
            foreach($row as $col)
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }
    }

}