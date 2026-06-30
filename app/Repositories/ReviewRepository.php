<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model
            ->with(['user', 'product'])
            ->latest()
            ->paginate($perPage);
    }
}
