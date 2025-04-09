<?php

namespace Paparee\BaleCms\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ThemeManagement extends Model
{
    use HasFactory;
    use HasUuids;
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logAll()
                ->logOnlyDirty()
                ->setDescriptionForEvent(fn(string $eventName) => "theme has been {$eventName}")
                ->useLogName('Theme')
                ->dontSubmitEmptyLogs()
                ;
    }
}
