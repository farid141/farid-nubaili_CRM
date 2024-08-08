<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime:Y-m-d',
        ];
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function details()
    {
        return $this->hasMany(ProjectDetail::class);
    }
}
