<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Funding extends Model
{
    use HasFactory;

    public function ukms()
{
        return $this->belongsTo(Ukm::class);
    }
}
