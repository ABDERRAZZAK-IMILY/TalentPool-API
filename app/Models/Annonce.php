<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Annonce",
 *     title="Annonce",
 *     description="Annonce model",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="recruteur_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="titre", type="string", example="Software Developer Position"),
 *     @OA\Property(property="description", type="string", example="We are looking for a talented developer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruteur_id',
        'titre',
        'description',
    ];

    public function candidatures()
    {
        return $this->belongsToMany(Candidatures::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }
}
