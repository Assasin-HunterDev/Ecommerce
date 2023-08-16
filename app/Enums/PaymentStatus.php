<?php

namespace App\Enums;

/**
 * Enum PaymentStatus represents the status of a payment.
 *
 * @package App\Enums
 */
enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
}
