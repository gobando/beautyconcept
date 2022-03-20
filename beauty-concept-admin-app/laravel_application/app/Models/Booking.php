<?php
/*
 * File name: Booking.php
 * Last modified: 2022.02.24 at 21:11:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Models;

use App\Casts\EServiceCollectionCast;
use App\Casts\OptionCollectionCast;
use App\Casts\TaxCollectionCast;
use App\Events\BookingCreatingEvent;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;

/**
 * Class Booking
 * @package App\Models
 * @version January 25, 2021, 9:22 pm UTC
 *
 * @property int id
 * @property User user
 * @property BookingStatus bookingStatus
 * @property Payment payment
 * @property Salon salon
 * @property EService[] e_services
 * @property Option[] options
 * @property integer quantity
 * @property integer user_id
 * @property integer address_id
 * @property integer booking_status_id
 * @property integer payment_status_id
 * @property Address address
 * @property integer payment_id
 * @property double duration
 * @property boolean at_salon
 * @property Coupon coupon
 * @property Tax[] taxes
 * @property Date booking_at
 * @property Date start_at
 * @property Date ends_at
 * @property string hint
 * @property boolean cancel
 */
class Booking extends Model
{

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'employee_id' => 'nullable|exists:users,id',
        'booking_status_id' => 'required|exists:booking_statuses,id',
        'payment_id' => 'nullable|exists:payments,id'
    ];
    public $table = 'bookings';
    public $fillable = [
        'salon',
        'e_services',
        'options',
        'quantity',
        'user_id',
        'employee_id',
        'booking_status_id',
        'address',
        'payment_id',
        'coupon',
        'taxes',
        'booking_at',
        'start_at',
        'ends_at',
        'hint',
        'cancel'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'salon' => Salon::class,
        'e_services' => EServiceCollectionCast::class,
        'options' => OptionCollectionCast::class,
        'address' => Address::class,
        'coupon' => Coupon::class,
        'taxes' => TaxCollectionCast::class,
        'booking_status_id' => 'integer',
        'payment_id' => 'integer',
        'duration' => 'double',
        'quantity' => 'integer',
        'user_id' => 'integer',
        'employee_id' => 'integer',
        'booking_at' => 'datetime:Y-m-d\TH:i:s.uP',
        'start_at' => 'datetime:Y-m-d\TH:i:s.uP',
        'ends_at' => 'datetime:Y-m-d\TH:i:s.uP',
        'hint' => 'string',
        'cancel' => 'boolean'
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'duration',
        'at_salon',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => BookingCreatingEvent::class,
        'updating' => BookingCreatingEvent::class,
    ];

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getDurationAttribute(): float
    {
        return $this->getDurationInHours();
    }

    public function getDurationInHours(): float
    {
        if ($this->start_at) {
            if ($this->ends_at) {
                $endAt = new Carbon($this->ends_at);
            } else {
                $endAt = (new Carbon())->now();
            }
            $startAt = new Carbon($this->start_at);
            $hours = $endAt->diffInSeconds($startAt) / 60 / 60;
            $hours = round($hours, 2);
        } else {
            $hours = 0;
        }
        return $hours;
    }

    public function getAtSalonAttribute(): bool
    {
        return $this->address->id == $this->salon->address->id;
    }

    /**
     * @return BelongsTo
     **/
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     **/

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    /**
     * @return BelongsTo
     **/
    public function bookingStatus()
    {
        return $this->belongsTo(BookingStatus::class, 'booking_status_id', 'id');
    }

    /**
     * @return BelongsTo
     **/
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function getTotal(): float
    {
        $total = $this->getSubtotal();
        $total += $this->getTaxesValue();
        $total -= $this->getCouponValue();
        return $total;
    }

    public function getSubtotal(): float
    {
        $total = 0;
        foreach ($this->e_services as $eService) {
            $total += $eService->getPrice() * ($this->quantity >= 1 ? $this->quantity : 1);
        }
        foreach ($this->options as $option) {
            $total += $option->price * ($this->quantity >= 1 ? $this->quantity : 1);
        }
        return $total;
    }

    public function getTaxesValue(): float
    {
        $total = $this->getSubtotal();
        $taxValue = 0;
        foreach ($this->taxes as $tax) {
            if ($tax->type == 'percent') {
                $taxValue += ($total * $tax->value / 100);
            } else {
                $taxValue += $tax->value;
            }
        }
        return $taxValue;
    }

    public function getCouponValue()
    {
        return $this->coupon->value;
    }

}
