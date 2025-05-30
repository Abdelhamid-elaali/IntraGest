<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    public function index()
    {
        $categories = HelpCategory::with('articles')->get();
        $popularArticles = HelpArticle::where('is_published', true)
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        return view('help-center.index', compact('categories', 'popularArticles'));
    }

    public function show($slug)
    {
        $article = HelpArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment view count
        $article->increment('view_count');

        return view('help-center.show', compact('article'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $articles = HelpArticle::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->paginate(10);

        return view('help-center.search', compact('articles', 'query'));
    }

    public function category($slug)
    {
        $category = HelpCategory::where('slug', $slug)->firstOrFail();
        $articles = $category->articles()
            ->where('is_published', true)
            ->paginate(10);

        return view('help-center.category', compact('category', 'articles'));
    }
    
    public function contact()
    {
        return view('help-center.contact');
    }
    
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // In a real application, you would send this to an email service
        // or save it to a database table for support staff to review
        
        return redirect()->route('help-center.contact')->with('success', 'Your message has been sent. Our support team will contact you soon.');
    }
}
