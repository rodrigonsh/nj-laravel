<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'need',
        'confession',
        'helper_1',
        'helper_2',
        'is_resolved',
        'expires_at',
        'resolved_at',
        'comments',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helper1()
    {
        return $this->belongsTo(User::class, 'helper_1');
    }

    public function helper2()
    {
        return $this->belongsTo(User::class, 'helper_2');
    }

}
