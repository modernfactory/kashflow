<?php

/*
 * PHP wrapper for Kashflow SOAP API
 *
 * Copyright (c) 2016 Patryk Antkowiak.
 * Free to use under the MIT licence, for full details view the LICENCE file.
 *
 */

namespace Kashflow;

use Accounting\Abstracts\PrintInvoice as AccountingPrintInvoice;
use Accounting\Traits\Dated;

class PrintInvoice extends AccountingPrintInvoice
{
    use Dated;

    /*
     * Convert model to API compatible format
     *
     */
    public static function decode($data)
    {
        $class = __CLASS__;
        $printInvoice = new $class;
        $printInvoice->printInvoiceResult = $data->PrintInvoiceResult;
        $printInvoice->status = $data->Status;
        $printInvoice->statusDetail = $data->StatusDetail;

        return $printInvoice;
    }

    /*
     * Convert API response back to model
     *
     */

    public static function uid(\Accounting\Interfaces\Model $model, $data)
    {
        if (isset($data->PrintInvoiceResult)) $model->id = $data->PrintInvoiceResult;
        return $model;
    }

    /*
     * Get unique id after object creationg
     *
     */

    public function encode()
    {
        $printInvoice = array(
            'InvoiceNumber' => $this->invoiceNumber
        );

        return array('PrintInvoice' => $printInvoice);
    }
}