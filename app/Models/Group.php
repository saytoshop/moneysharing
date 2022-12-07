<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner',
        'name',
        'token',
        'token_expired'
        ];
    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function admins(){
        return $this->belongsToMany(User::class, 'admin_group', 'group_id', 'user_id');
    }
    public function operations(){
        return $this->hasMany(Operation::class)->orderByDesc('id');
    }
    public function isTokenExpired(){
        return $this->token_expired && $this->token_expired < date('Y-m-d H:i:s');
    }
    public function budget(){
        $budget = 0;
        $ops = $this->operations()->whereNotNull('type')->get();
        foreach ($ops as $operation) {
            $budget += $operation->amount;
        }
        return $budget;
    }
//    public function mainOperations(){
//        return $this->operations()->whereNotNull('type')->get();
//    }
//    public function operationsByUsers(){
//        return $this->operations()->whereNull('type')->get();
//    }
}
