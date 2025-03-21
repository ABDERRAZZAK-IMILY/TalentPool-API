<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonces extends Model
{
    use HasFactory;

 protected $fillable = [
   'recruteur_id',
   'titre' ,
   'description',
 ];

 public function candidatures(){
   
  return  $this->BelongsToMany(candidatures::class);
 }
 
 public function Users(){

  return $this->belongsTo(User::class);
 }

}
