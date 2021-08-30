<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = "positions";

    protected $fillable = ["name","admin_created_id","admin_updated_id"];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m-d-Y");
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("m-d-Y");
    }
}
