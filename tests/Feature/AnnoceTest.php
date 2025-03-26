<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Annonce;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnoceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_user_can_create_announcement()
    {
        $annoceData = [
            'titre' => 'Test Announcement',
            'description' => 'This is a test announcement',
            'recruteur_id' => $this->user->id
        ];

        $response = $this->postJson('/api/annonces', $annoceData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'titre',
                    'description',
                    'recruteur_id',
                    'created_at'
                ]
            ]);
    }

    public function test_user_can_get_all_announcements()
    {
        Annonce::factory()->count(3)->create();

        $response = $this->getJson('/api/annonces');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'titre',
                        'description',
                        'recruteur_id',
                        'created_at'
                    ]
                ]
            ]);
    }

    public function test_user_can_get_single_announcement()
    {
        $annoce = Annonce::factory()->create();

        $response = $this->getJson("/api/annonces/{$annoce->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $annoce->id,
                    'titre' => $annoce->titre,
                    'description' => $annoce->description
                ]
            ]);
    }

    public function test_user_can_update_announcement()
    {
        $annoce = Annonce::factory()->create(['recruteur_id' => $this->user->id]);
        $updateData = [
            'titre' => 'Updated Title',
            'description' => 'Updated description'
        ];

        $response = $this->putJson("/api/annonces/{$annoce->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'titre' => 'Updated Title',
                    'description' => 'Updated description'
                ]
            ]);
    }

    public function test_user_can_delete_announcement()
    {
        $annoce = Annonce::factory()->create(['recruteur_id' => $this->user->id]);

        $response = $this->deleteJson("/api/annonces/{$annoce->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('annonces', ['id' => $annoce->id]);
    }
}
