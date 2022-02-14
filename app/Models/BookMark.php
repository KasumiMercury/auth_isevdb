<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMark extends Model
{
    use HasFactory;
    protected $table = 'BookMarks';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
 
    public function player() {
        return $this->belongsTo('App\Models\Players');
    }
}
