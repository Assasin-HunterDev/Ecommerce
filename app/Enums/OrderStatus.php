<?php

namespace App\Enums;

/**
 * Enum OrderStatus represents the status of an order.
 *
 * @package App\Enums
 */
enum OrderStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Shipped = 'shipped';
    case Completed = 'completed';
}
