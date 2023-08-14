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
    const Shipping = 'shipping';

    /**
     * The address type for billing.
     */
    const Billing = 'billing';
}
