<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    // Table Name
    protected $table = 'user_profiles';
    
    //Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user_id','address','current_school','previous_school','parents_details','profile_picture'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
