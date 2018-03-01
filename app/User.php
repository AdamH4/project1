<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Notifications\UserResetPasswordNotification;
use App\Notifications\Verify;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return !is_null(DB::table('admins')->find($this->id));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public static function verified($id){
        return static::selectRaw('id, name, token')
            ->where([
                ['id','=',$id],
                ['token','=',null]
            ])
            ->get();
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}