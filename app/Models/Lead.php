<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function products($lead_id)
    {
        $products = DB::table('leads AS l')
            ->where('l.id', $lead_id)
            ->select([
                'l.name AS lead_name',
                'l.contact AS contact',
                'p2.name AS product_name',
                DB::raw('SUM(pd.quantity) AS quantity')
            ])
            ->leftJoin('projects AS p', 'p.lead_id', '=', 'l.id')
            ->leftJoin('project_details AS pd', 'pd.project_id', '=', 'p.id')
            ->leftJoin('products AS p2', 'pd.product_id', '=', 'p2.id')
            ->groupBy('l.name', 'l.contact', 'p2.name')
            ->get();
        return $products;
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function scopeFilter(Builder $query, Request $request): void
    {
        $query->when($request['have_product'] ?? false, function ($query) {
            return $query->whereHas('projects.details.product');
        });
    }
}
