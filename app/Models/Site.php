<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain',
        'currency',
        'price_guest_post',
        'price_link_insertion',
        'niche',
        'minimum_words',
        'accept_igaming',
    ];
}
