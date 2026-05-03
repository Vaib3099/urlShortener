<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];

    // A client can have many users (admins + regular users)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Shortcut: get the default admin for this client
    public function admin()
    {
        return $this->hasOne(User::class)->whereHas('role', function ($query) {
            $query->where('name', 'admin');
        });
    }

    public function urls()
    {
        return $this->hasMany(Url::class);
    }
}
