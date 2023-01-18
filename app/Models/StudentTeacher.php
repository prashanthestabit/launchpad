<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTeacher extends Model
{
    use HasFactory;

    use HasFactory;

    // Table Name
    protected $table = 'student_teachers';

    //Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user_id','teacher_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id');
    }
}
