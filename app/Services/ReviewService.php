<?php

namespace App\Services;

use App\Repositories\ReviewRepository;

class ReviewService
{
    protected ReviewRepository $repository;

    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function approveReview(int $id): bool
    {
        return $this->repository->update($id, ['is_approved' => true, 'is_hidden' => false]);
    }

    public function hideReview(int $id): bool
    {
        return $this->repository->update($id, ['is_hidden' => true]);
    }

    public function deleteReview(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
