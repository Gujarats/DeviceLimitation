<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * Give specific table for this model
     */
    protected $table ='devices';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

}
