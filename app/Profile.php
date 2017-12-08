<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at'];

    protected $fillable = [
        'avatar_url', 'location', 'website', 'bio'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
