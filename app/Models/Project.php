<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime:Y-m-d',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
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

    public function scopeFilter(Builder $query, Request $request): void
    {
        $query->when($request['status'] ?? false, function ($query, $status) {
            return $query->where('status', $status);
        });
        $query->when($request['manager_id'] ?? false, function ($query, $manager_id) {
            return $query->where('manager_id', $manager_id);
        });
        $query->when($request['sales_id'] ?? false, function ($query, $sales_id) {
            return $query->where('sales_id', $sales_id);
        });
        $query->when($request['lead_id'] ?? false, function ($query, $lead_id) {
            return $query->where('lead_id', $lead_id);
        });
    }
}
