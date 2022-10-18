<?php

namespace App\Models;

use App\Traits\CustomAttributesTrait;
use Illuminate\Database\Eloquent\Model;

class Shopper extends Model
{
    use CustomAttributesTrait;

    protected $table = 'shoppers';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $upper_fields = [
        'full_name',
        'email'
    ];

    protected $fillable = [
        'document',
        'full_name',
        'cellphone',
        'email',
        'status'
    ];
}
