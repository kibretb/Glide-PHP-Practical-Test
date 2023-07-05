<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationallyUniqueIdentifier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const RandomMacIndicators = ['2','6','A','E'];
}
