<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordService extends Model
{
    use HasFactory;

    protected $fillable = ['record_id', 'service_id','total_price', 'total_duration'];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
