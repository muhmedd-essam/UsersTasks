<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    use HasFactory;

    protected $dates = ['deadline'];
    protected $table = 'tasks';
    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
        'user_id',
    ];
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
