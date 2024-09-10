<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $table = 'application_status';

    protected $fillable = [
        'application_id', 'status', 'reason', 'department_id', 'updated_by'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function user()
    {
        return $this->application->user();
    }

    public function applicationStatus()
    {
        return $this->hasMany(ApplicationStatus::class);
    }
}