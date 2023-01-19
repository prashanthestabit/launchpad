<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    use HasFactory;

    // Table Name
    protected $table = 'user_experiences';

    //Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user_id','experience_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}
