<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Translator extends Model
{
    use Translatable;

    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    public array $translatedAttributes = [
        'value',
    ];

    protected $fillable = [
        'key',
    ];
}
