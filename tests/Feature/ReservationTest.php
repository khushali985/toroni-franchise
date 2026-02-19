<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Franchise;
use App\Models\RestaurantTable;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_can_be_created_successfully()
    {
        // Create franchise
        $franchise = Franchise::factory()->create();

        // Create available table
        RestaurantTable::factory()->create([
            'franchise_id' => $franchise->id,
            'capacity_people' => 4,
            'status' => 'available'
        ]);

        $response = $this->post('/reservation/store', [
            'full_name' => 'Rahul Patel',
            'phone_no' => '9876543210',
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '18:00',
            'no_of_people' => 2,
            'franchise_id' => $franchise->id,
            'transaction_id' => 'TXN12345',
            'payment_proof' => UploadedFile::fake()->image('proof.jpg')
        ]);

        $response->assertSessionHas('success','Reservation created successfully!');

        $this->assertDatabaseHas('reservations', [
            'phone_no' => '9876543210',
            'franchise_id' => $franchise->id
        ]);
    }
}
