<?php
namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReservationsExport implements FromCollection
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        return Reservation::whereIn('id', $this->ids)->get([
            'id',
            'full_name',
            'phone_no',
            'date',
            'time',
            'status',
            'amount'
        ]);
    }
}