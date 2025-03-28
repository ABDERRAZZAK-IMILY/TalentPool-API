<?php

namespace Tests\Feature;

use App\Models\Annonce;
use App\Models\candidatures;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CandidaturesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $recruiter;
    protected $candidate;
    protected $adminToken;
    protected $recruiterToken;
    protected $candidateToken;
    protected $annonce;

    public function setUp(): void
    {
        parent::setUp();

        // Create users with different roles
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->recruiter = User::factory()->create(['role' => 'recruteur']);
        $this->candidate = User::factory()->create(['role' => 'candidat']);

        // Generate tokens
        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->recruiterToken = JWTAuth::fromUser($this->recruiter);
        $this->candidateToken = JWTAuth::fromUser($this->candidate);

        // Create a job posting
        $this->annonce = Annonce::factory()->create([
            'recruteur_id' => $this->recruiter->id,
            'titre' => 'Test Job Posting',
            'description' => 'This is a test job posting'
        ]);
    }

    public function testGetAllCandidatures()
    {
        // Create some applications
        candidatures::factory()->count(3)->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidate->id
        ]);

        // Make the request as admin
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/candidatures');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'candidat_id',
                        'annonce_id',
                        'cv',
                        'lettre_motivation',
                        'statut',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    public function testCreateCandidature()
    {
        $candidatureData = [
            'annonce_id' => $this->annonce->id,
            'cv' => 'Test CV content',
            'lettre_motivation' => 'Test cover letter content'
        ];

        // Make the request as candidate
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->candidateToken,
        ])->postJson('/api/candidatures', $candidatureData);

        // Assert response
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'candidat_id',
                    'annonce_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ]);

        // Assert the application was created with the correct data
        $this->assertDatabaseHas('candidatures', [
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidate->id,
            'cv' => 'Test CV content',
            'lettre_motivation' => 'Test cover letter content',
            'statut' => 'en_attente' // Default status
        ]);
    }

    public function testGetSingleCandidature()
    {
        // Create an application
        $candidature = candidatures::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidate->id
        ]);

        // Make the request as candidate
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->candidateToken,
        ])->getJson('/api/candidatures/' . $candidature->id);

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'candidat_id',
                    'annonce_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $candidature->id,
                    'candidat_id' => $this->candidate->id,
                    'annonce_id' => $this->annonce->id
                ]
            ]);
    }

    public function testUpdateCandidatureAsRecruiter()
    {
        // Create an application
        $candidature = candidatures::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidate->id,
            'statut' => 'en_attente'
        ]);

        $updateData = [
            'statut' => 'acceptée'
        ];

        // Make the request as recruiter
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->recruiterToken,
        ])->putJson('/api/candidatures/' . $candidature->id, $updateData);

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'candidat_id',
                    'annonce_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $candidature->id,
                    'statut' => 'acceptée'
                ]
            ]);

        // Assert the application was updated
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'acceptée'
        ]);
    }

    public function testUpdateCandidatureAsCandidate()
    {
        // Create an application
        $candidature = candidatures::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidate->id,
            'cv' => 'Original CV content',
            'lettre_motivation' => 'Original cover letter content'
        ]);

        $updateData = [
            'cv' => 'Updated CV content',
            'lettre_motivation' => 'Updated cover letter content'
        ];

        // Make the request as candidate
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->candidateToken,
        ])->putJson('/api/candidatures/' . $candidature->id, $updateData);

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'candidat_id',
                    'annonce_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $candidature->id,
                    'cv' => 'Updated CV content',
                    'lettre_motivation' => 'Updated cover letter content'
                ]
            ]);

        // Assert the application was updated
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'cv' => 'Updated CV content',
            'lettre_motivation' => 'Updated cover letter content'
        ]);
    }

    public function testDeleteCandidatureAsCandidate()
    {
        // Create an application
        $candidature = candidatures::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidate->id
        ]);

        // Make the request as candidate
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->candidateToken,
        ])->deleteJson('/api/candidatures/' . $candidature->id);

        // Assert response
        $response->assertStatus(204);

        // Assert the application was deleted
        $this->assertDatabaseMissing('candidatures', [
            'id' => $candidature->id
        ]);
    }

    public function testCandidateCannotUpdateOthersCandidature()
    {
        // Create another candidate
        $otherCandidate = User::factory()->create(['role' => 'candidat']);
        $otherCandidateToken = JWTAuth::fromUser($otherCandidate);

        // Create an application for the other candidate
        $candidature = candidatures::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $otherCandidate->id
        ]);

        $updateData = [
            'cv' => 'Updated CV content',
            'lettre_motivation' => 'Updated cover letter content'
        ];

        // Make the request as the original candidate
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->candidateToken,
        ])->putJson('/api/candidatures/' . $candidature->id, $updateData);

        // Assert unauthorized
        $response->assertStatus(403);

        // Assert the application was not updated
        $this->assertDatabaseMissing('candidatures', [
            'id' => $candidature->id,
            'cv' => 'Updated CV content'
        ]);
    }

    public function testCandidateCannotDeleteOthersCandidature()
    {
        $otherCandidate = User::factory()->create(['role' => 'candidat']);
        $otherCandidateToken = JWTAuth::fromUser($otherCandidate);

        $candidature = candidatures::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $otherCandidate->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->candidateToken,
        ])->deleteJson('/api/candidatures/' . $candidature->id);

        $response->assertStatus(403);

        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id
        ]);
    }
}