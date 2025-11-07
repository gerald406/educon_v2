<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    /** @use HasFactory<\Database\Factories\SystemSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'key_name',
        'value',
        'description',
        'data_type',
        'module',
        'is_editable',
    ];

    protected $casts = [
        'is_editable' => 'boolean',
    ];
}
