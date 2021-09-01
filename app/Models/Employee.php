<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    use HasFactory;


    protected $table = "employees";

    protected $fillable = ["fullname", "position", "phone", "email", "salary", "dateOfEmployment", "photo", "admin_created_id", "admin_updated_id", "boss_id"];
}
