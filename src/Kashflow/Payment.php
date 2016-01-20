<?php

/*
 * PHP wrapper for Kashflow SOAP API
 *
 * Copyright (c) 2016 Patryk Antkowiak.
 * Free to use under the MIT licence, for full details view the LICENCE file.
 *
 */

namespace Kashflow;

use Accounting\Abstracts\Payment as AccountingPayment;
use Accounting\Traits\Dated;

class Payment extends AccountingPayment
{
    use Dated;

    /*
     * Convert model to API compatible format
     *
     */
    public static function decode($data)
    {
        $decode = array();
        $single = false;
        // get correct data
        if (isset($data->GetPayments_RecentResult)) {
            $data = $data->GetPayments_RecentResult->Payment;
            $data = is_array($data) ? $data : array($data);
        } else if (isset($data->GetPaymentByIDResult)) {
            $data = array($data->GetPaymentByIDResult);
            $single = true;
        } else {
            return false;
        }
        // decode
        foreach ($data as $object) {
            $class = __CLASS__;
            $payment = new $class;
            $payment->id = $object->PayID;
            $payment->payInvoice = $object->PayInvoice;
            $payment->payDate = $object->PayDate;
            $payment->payNote = $object->PayNote;
            $payment->payMethod = $object->PayMethod;
            $payment->payAccount = $object->PayAccount;
            $payment->payAmount = $object->PayAmount;

            $decode[] = $payment;
        }
        return $single ? $decode[0] : $decode;
    }

    /*
     * Convert API response back to model
     *
     */

    public static function uid(\Accounting\Interfaces\Model $model, $data)
    {
        if (isset($data->InsertPaymentResult)) $model->id = $data->InsertPaymentResult;
        return $model;
    }

    /*
     * Get unique id after object creationg
     *
     */

    public function encode()
    {
        $payment = array(
            'PayID' => 0,
            'PayInvoice' => $this->payInvoice,
            'PayDate' => self::apiDate($this->payDate),
            'PayNote' => $this->payNote,
            'PayMethod' => $this->payMethod,
            'PayAccount' => $this->payAccount,
            'PayAmount' => $this->payAmount
        );

        return array('InvoicePayment' => $payment);
    }
}