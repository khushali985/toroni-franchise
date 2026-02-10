class RestaurantTable extends Model
{
    protected $fillable = [
        'franchise_id',
        'table_no',
        'capacity_people'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }
}
