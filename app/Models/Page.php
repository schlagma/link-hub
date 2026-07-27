<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mews\Purifier\Facades\Purifier;

class Page extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'groups',
        'public',
    ];

    protected function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['description'] = $value === null || $value === ''
            ? $value
            : Purifier::clean($value);
    }
}
