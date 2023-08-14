<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Customer represents a customer in the system
 *
 * @package App\Models
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'status'];

    /**
     * Get the user associated with the customer.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the customer's addresses.
     *
     * @return HasOne
     */
    private function _getAddresses(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'user_id');
    }

    /**
     * Get the customer's shipping address.
     *
     * @return HasOne
     */
    public function shippingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Shipping->value);
    }

    /**
     * Get the customer's billing address.
     *
     * @return HasOne
     */
    public function billingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Billing->value);
    }
}
