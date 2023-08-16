<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Order represents an order in the application.
 *
 * @package App\Models
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status', 'total_price', 'created_by', 'updated_by'];

    /**
     * Check if the order is paid.
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid->value;
    }

    /**
     * Get the payment associated with the order.
     *
     * @return HasOne
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the user who created the order.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the order items associated with the order.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Delete unpaid orders older than the specified hours.
     *
     * @param int $hours The number of hours before which orders should be deleted.
     * @return int The number of deleted orders.
     */
    public static function deleteUnpaidOrders(int $hours): int
    {
        return Order::query()
            ->where('status', OrderStatus::Unpaid->value)
            ->where('created_at', '<', Carbon::now()->subHours($hours))
            ->delete();
    }
}
