<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $validated)
 * @method static get()
 */
class Order extends Model
{
    const STATUS_CREATED = "CREATED";
    const STATUS_PAYED = "PAYED";
    const STATUS_REJECTED = "REJECTED";

    protected $fillable = [
        'customer_name', 'customer_email', 'customer_mobile', 'status'
    ];

    protected $appends = [
        'is_created',
        'is_payed',
        'is_rejected'
    ];

    public function getIsCreatedAttribute()
    {
        return $this->status == self::STATUS_CREATED;
    }

    public function getIsPayedAttribute()
    {
        return $this->status == self::STATUS_PAYED;
    }

    public function getIsRejectedAttribute()
    {
        return $this->status == self::STATUS_REJECTED;
    }

}
