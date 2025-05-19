<?php

namespace Paparee\BaleCms\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_uuid',
        'database',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
