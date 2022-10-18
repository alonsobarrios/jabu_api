<?php

namespace App\Models;

use App\Traits\CustomAttributesTrait;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use CustomAttributesTrait;

    protected $table = 'tickets';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $upper_fields = [
        'description'
    ];

    protected $fillable = [
        'code',
        'description',
        'cost'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function booking()
    {
        return $this->hasOne(Booking::class)->where('status', 1);
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
            'code' => $this->code ?? '',
            'description' => $this->description ?? '',
            'cost' => $this->cost ?? '',
            'status' => $this->booking ? 'RESERVADA' : 'DISPONIBLE'
        ];
    }
}
