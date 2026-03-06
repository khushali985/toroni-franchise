<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'franchise_id',
        'upi_name',
        'qr_image'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}