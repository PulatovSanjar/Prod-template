<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Translator extends Model
{
    use Translatable;

    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public array $translatedAttributes = [
        'value'
    ];

    protected $fillable = [
        'key'
    ];
}
