<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }

    public function operation()
    {
        return $this->belongsTo(self::class, 'id', 'operation_id');
    }

    public function operationsByUsers()
    {
        return $this->hasMany(self::class, 'operation_id');
    }
}
