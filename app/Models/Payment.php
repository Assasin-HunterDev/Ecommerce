<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Payment represents a payment in the application.
 *
 * @package App\Models
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'status', 'amount', 'type', 'session_id', 'created_by', 'updated_by'];

    /**
     * Get the order associated with the payment.
     *
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
