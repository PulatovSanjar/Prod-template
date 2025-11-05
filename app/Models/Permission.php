<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    /**
     * @var array
     */
    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
