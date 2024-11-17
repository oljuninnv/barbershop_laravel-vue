<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'worker_id', 'date', 'time', 'user_name', 'user_phone', 'user_email','is_finished'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function recordServices()
    {
        return $this->hasMany(RecordService::class);
    }
}
