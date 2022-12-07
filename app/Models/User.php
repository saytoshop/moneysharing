<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;


class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'permissions',
        'multiplicator'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function created_operations()
    {
        return $this->hasMany(Operation::class, 'operator_id');
    }

    public function isGroupAdmin(Group $group)
    {
        foreach ($group->admins as $u) {
            if ($this->id == $u->id) return true;
        }
        return false;
    }

    public function isMemberOf(Group $group)
    {
        foreach ($group->users as $u) {
            if ($this->id == $u->id) return true;
        }
        return false;
    }

    public function personalBudget(Group $group)
    {
        $userOperations = $this->operations()
            ->where('group_id', $group->id)->get();
//        dd($userOperations);
        $value = 0;
        foreach ($userOperations as $operation) {
            $value += $operation->amount;
        }
        return $value;
    }
}
