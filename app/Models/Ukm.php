<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ukm extends Model
{
    use HasFactory;

    public function Funding()
    {
        return $this->hasMany(Funding::class);
    }

    // public function Funding(): HasOne
    // {
    //     return $this->hasOne(Funding::class);
    // }
}
