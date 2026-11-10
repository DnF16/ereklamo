<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Complaint;

class Complaint extends Model
{
    /** @use HasFactory<\Database\Factories\ComplaintFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'description',
        'category',
        'priority',
        'latitude',
        'longitude',
        'status',
        'image',
        'status_updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the user that submitted the complaint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusUpdatedBy()
    {
        return $this->belongsTo(
            User::class,
            'status_updated_by'
        );
    }
}
