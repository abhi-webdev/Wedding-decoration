<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display the FAQ page grouped by category.
     */
    public function index()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $categories = [
            'Booking',
            'Decorations',
            'Pricing',
            'Locations',
            'Cancellation',
            'Payments',
            'General',
        ];

        // Group by category
        $groupedFaqs = [];
        foreach ($categories as $cat) {
            $groupedFaqs[$cat] = $faqs->where('category', $cat);
        }

        return view('faq.index', compact('groupedFaqs', 'categories'));
    }
}
