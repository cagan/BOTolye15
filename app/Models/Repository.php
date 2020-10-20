<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function mails()
    {
        return $this->belongsToMany(Mail::class);
    }

}
