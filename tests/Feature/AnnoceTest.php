<?php

use App\Models\Annonce;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;



beforeEach(function () {
    // Clear database before each test
    Annonce::query()->delete();
});

test('can fetch all annonces', function () {
    $annonces = Annonce::factory(3)->create();

    get('/api/annonces')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'created_at', 'updated_at']
            ]
        ]);
});

test('can create new annonce', function () {
    $annonceData = [
        'title' => 'Software Developer',
        'description' => 'We are looking for a PHP developer',
        'user_id' => 1
    ];

    post('/api/annonces', $annonceData)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'title', 'description', 'created_at', 'updated_at']
        ]);

    $this->assertDatabaseHas('annonces', $annonceData);
});

test('can fetch single annonce', function () {
    $annonce = Annonce::factory()->create();

    get("/api/annonces/{$annonce->id}")
        ->assertStatus(200)
        ->assertJson([
            'data' => ['id' => $annonce->id]
        ]);
});

test('can update annonce', function () {
    $annonce = Annonce::factory()->create();
    $updateData = ['title' => 'Updated Title'];

    put("/api/annonces/{$annonce->id}", $updateData)
        ->assertStatus(200)
        ->assertJson([
            'data' => ['title' => 'Updated Title']
        ]);
});

test('can delete annonce', function () {
    $annonce = Annonce::factory()->create();

    delete("/api/annonces/{$annonce->id}")
        ->assertStatus(204);

    $this->assertDatabaseMissing('annonces', ['id' => $annonce->id]);
});