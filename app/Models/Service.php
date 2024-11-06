<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'execution_time'];

    public function recordServices()
    {
        return $this->hasMany(RecordService::class);
    }
}
