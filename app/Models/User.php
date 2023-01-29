<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class User extends Authenticatable
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'role',
        'status',
        'api_token',
        'token_expire_at',
        'otp',
        'commission',
        'dp',
        'address',
        'city',
        'pincode',
        'state',
        'country',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function isAdministrator(){
        if(($this->role == 'Administrator' && $this->status == 'Active')||($this->roles()->where('role', 'Administrator')->where('status', 'Active')->exists())){
            return true;
        }
        return false;
    }

    public function isCustomer(){
        if(($this->role == 'Customer' && $this->status == 'Active')||($this->roles()->where('role', 'Customer')->where('status', 'Active')->exists())){
            return true;
        }
        return false;
    }

}
