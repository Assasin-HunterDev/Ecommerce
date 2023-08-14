<?php

namespace App\Enums;

/**
 * Enum AddressType represents the type of address (e.g., shipping or billing).
 *
 * @package App\Enums
 */
enum AddressType: string
{
    /**
     * The address type for shipping.
     */
    case Shipping = 'shipping';

    /**
     * The address type for billing.
     */
    case Billing = 'billing';
}
