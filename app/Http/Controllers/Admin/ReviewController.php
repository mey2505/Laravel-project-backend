<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    protected ReviewService $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $reviews = $this->service->getPaginated();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $this->service->approveReview($review->id);
        return back()->with('success', 'Review approved.');
    }

    public function hide(Review $review)
    {
        $this->service->hideReview($review->id);
        return back()->with('success', 'Review hidden.');
    }

    public function destroy(Review $review)
    {
        $this->service->deleteReview($review->id);
        return back()->with('success', 'Review deleted.');
    }
}
