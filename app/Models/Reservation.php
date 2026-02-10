class Reservation extends Model
{
    protected $fillable = [
        'full_name',
        'phone_no',
        'date',
        'time',
        'no_of_people',
        'franchise_id',
        'table_id'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }
}
