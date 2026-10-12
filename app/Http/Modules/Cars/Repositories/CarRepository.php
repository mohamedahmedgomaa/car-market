<?php

namespace App\Http\Modules\Cars\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Cars\Models\Car;
use Illuminate\Support\Facades\DB;

class CarRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Car $model
     */
    public function __construct(Car $model)
    {
        $this->model = $model;
        $this->baseFilters[] = 'offset';
        $this->baseFilters[] = 'limit';
    }

    public function getModel(): Car
    {
        return $this->model;
    }

    public static function getListingSelectColumns(): array
    {
        return [
            'id',
            'seller_id',
            'brand_id',
            'model_id',
            'city_id',
            'governorate_id',
            'country_id',
            'type',
            'title',
            'price',
            'currency',
            'year',
            'mileage',
            'transmission',
            'fuel_type',
            'drivetrain',
            'color',
            'condition',
            'status',
            'is_best_deal',
            'is_import',
            'is_featured',
            'show_on_home',
            'is_global_ad',
            'created_at',
            'updated_at',
        ];
    }

    public static function getListingRelations(): array
    {
        return [
            'brand:id,name',
            'model:id,name',
            'city:id,name',
            'governorate:id,name',
            'seller' => function ($q) {
                $q->select('id', 'name', 'store_name', 'tier', 'is_verified', 'city_id', 'governorate_id');
            },
            'seller.city:id,name',
            'seller.governorate:id,name',
            'images' => function ($q) {
                $q->select('id', 'car_id', 'path', 'is_main');
            },
            'favorites' => function ($q) {
                $q->select('users.id');
            },
        ];
    }

    /**
     * Get all with safe pagination defaults to protect memory on large datasets.
     */
    public function getAll(array $attributes)
    {
        $typeFilter = $attributes['filter']['type'] ?? request()->input('filter.type') ?? null;
        $indexHint = $typeFilter ? 'cars USE INDEX (idx_cars_type_status_created_at, idx_cars_type_status_price, idx_cars_type_status_brand, idx_cars_type_status_best_deal)' : null;

        if (isset($attributes['offset']) && isset($attributes['limit'])) {
            $offset = max(0, (int) $attributes['offset']);
            $limit = max(1, (int) $attributes['limit']);

            // Optimized count query (pure index, no relations or order clauses)
            $countQuery = $this->model->newQuery();
            if ($indexHint) {
                $countQuery->from(DB::raw($indexHint));
            }
            $countBuilder = \Spatie\QueryBuilder\QueryBuilder::for($countQuery)
                ->allowedFilters($this->model->getAllowedFilters());
            $total = $countBuilder->count();

            $query = $this->model->select(self::getListingSelectColumns());
            if ($indexHint) {
                $query->from(DB::raw($indexHint));
            }
            $queryBuilder = \Spatie\QueryBuilder\QueryBuilder::for($query)
                ->allowedFilters($this->model->getAllowedFilters())
                ->allowedFields($this->model::getAllowedFields())
                ->allowedIncludes($this->model::getAllowedIncludes())
                ->allowedSorts($this->model::getAllowedSorts())
                ->defaultSort($this->model::getDefaultSort())
                ->with(self::getListingRelations())
                ->withCount(['favorites']);

            if (empty($attributes['sort']) && empty($attributes['sortBy'])) {
                $queryBuilder = $queryBuilder->latest();
            }

            $items = $queryBuilder->offset($offset)->limit($limit)->get();

            return new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $limit,
                (int) floor($offset / $limit) + 1
            );
        }

        $page = max(1, (int) ($attributes['page'] ?? 1));
        $perPage = max(1, (int) ($attributes['perPage'] ?? $attributes['per_page'] ?? 20));

        // Optimized count query (pure index, no relations or order clauses)
        $countQuery = $this->model->newQuery();
        if ($indexHint) {
            $countQuery->from(DB::raw($indexHint));
        }
        $countBuilder = \Spatie\QueryBuilder\QueryBuilder::for($countQuery)
            ->allowedFilters($this->model->getAllowedFilters());
        $total = $countBuilder->count();

        $query = $this->model->select(self::getListingSelectColumns());
        if ($indexHint) {
            $query->from(DB::raw($indexHint));
        }
        $queryBuilder = \Spatie\QueryBuilder\QueryBuilder::for($query)
            ->allowedFilters($this->model->getAllowedFilters())
            ->allowedFields($this->model::getAllowedFields())
            ->allowedIncludes($this->model::getAllowedIncludes())
            ->allowedSorts($this->model::getAllowedSorts())
            ->defaultSort($this->model::getDefaultSort())
            ->with(self::getListingRelations())
            ->withCount(['favorites']);

        if (empty($attributes['sort']) && empty($attributes['sortBy'])) {
            $queryBuilder = $queryBuilder->latest();
        }

        $items = $queryBuilder->forPage($page, $perPage)->get();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page
        );
    }
}
