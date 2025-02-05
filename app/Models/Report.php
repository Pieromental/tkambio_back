<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
   
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'report_id',
        'user_id',
        'title',
        'description',
        'report_link', 
        'active',
        'created_by',
        'updated_by',
    ];
}
