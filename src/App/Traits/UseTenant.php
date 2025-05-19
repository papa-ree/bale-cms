<?php

namespace Paparee\BaleCms\App\Traits;

use Paparee\BaleCms\App\Models\Tenant;

trait UseTenant
{
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'user_uuid', 'uuid');
    }
}