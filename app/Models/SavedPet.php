<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPet extends Model
{
    protected $table = 'saved_pets';

    protected $fillable = ['user_id', 'pet_id'];
}
