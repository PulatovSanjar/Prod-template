<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslatorTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'value'
    ];
}
