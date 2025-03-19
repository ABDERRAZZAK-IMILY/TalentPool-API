<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
