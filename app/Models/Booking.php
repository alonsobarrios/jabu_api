<?php

namespace App\Models;

use App\Traits\CustomAttributesTrait;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use CustomAttributesTrait;

    const ID_STR_LEN = 6;

    protected $table = 'bookings';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'ticket_id',
        'shopper_id',
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shopper()
    {
        return $this->belongsTo(Shopper::class);
    }

    /**
     * @return string
     */
    public function getBookingCodeAttribute()
    {
        return str_pad($this->id, self::ID_STR_LEN, '0', STR_PAD_LEFT);
    }


    /**
     * @param bool $format
     * @return array
     */
    public function toArray(bool $format = false)
    {
        if (!$format) {
            return parent::toArray();
        }

        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'ticket_code' => $this->ticket->code ?? '',
            'ticket_description' => $this->ticket->description ?? '',
            'ticket_cost' => $this->ticket->cost ?? '',
            'shopper_document' => $this->shopper->document ?? '',
            'shopper_full_name' => $this->shopper->full_name ?? '',
            'status' => $this->status
        ];
    }
}
