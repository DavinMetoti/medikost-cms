<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the flexible search endpoint
     */
    public function test_search_endpoint_returns_correct_structure(): void
    {
        // Create test data
        $product = Product::factory()->create([
            'name' => 'Kost Mawar Test',
            'address' => 'Jl. Test No. 123',
            'is_published' => true,
            'is_active' => true,
        ]);

        ProductDetail::factory()->create([
            'product_id' => $product->id,
            'room_name' => 'Room A',
            'price' => 500000,
            'is_active' => true,
            'available_rooms' => 2,
        ]);

        // Test basic search
        $response = $this->getJson('/api/v1/search?q=Mawar');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                                'address',
                                'distance_to_kariadi',
                                'thumbnail',
                                'starting_price',
                                'facilities_preview',
                                'status',
                                'room_available',
                                'total_rooms',
                                'product_details',
                                'created_at',
                            ]
                        ],
                        'current_page',
                        'total',
                    ],
                    'message',
                    'meta' => [
                        'timestamp',
                        'execution_time_ms',
                        'request_id',
                        'api_version',
                        'path',
                        'method',
                        'status_code',
                        'pagination',
                    ]
                ]);

        // Verify search results
        $data = $response->json('data');
        $this->assertEquals(1, $data['total']);
        $this->assertEquals('Kost Mawar Test', $data['data'][0]['name']);
        $this->assertNotEmpty($data['data'][0]['product_details']);
    }

    /**
     * Test search with filters
     */
    public function test_search_with_filters(): void
    {
        // Create test data
        $product = Product::factory()->create([
            'name' => 'Kost Melati',
            'distance_to_kariadi' => 2.5,
            'is_published' => true,
            'is_active' => true,
        ]);

        ProductDetail::factory()->create([
            'product_id' => $product->id,
            'price' => 600000,
            'is_active' => true,
        ]);

        // Test search with price filter
        $response = $this->getJson('/api/v1/search?min_price=500000&max_price=700000');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertGreaterThan(0, $data['total']);
    }

    /**
     * Test search returns empty results for no matches
     */
    public function test_search_returns_empty_for_no_matches(): void
    {
        $response = $this->getJson('/api/v1/search?q=nonexistent');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(0, $data['total']);
    }
}
