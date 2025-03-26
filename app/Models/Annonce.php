<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
