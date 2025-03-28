<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Candidature",
 *     title="Candidature",
 *     description="Candidature model",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="candidat_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="annonce_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="cv", type="string", example="path/to/cv.pdf"),
 *     @OA\Property(property="lettre_motivation", type="string", example="path/to/letter.pdf"),
 *     @OA\Property(property="statu", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class candidatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'annonce_id',
        'cv' ,
        'lettre_motivation',
        'statu',
      ];
}
