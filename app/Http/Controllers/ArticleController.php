<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::where('is_active', true)
            ->orderBy('published_date', 'desc');

        // Search by title
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category (if you have article categories)
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('published_date', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('published_date', 'desc');
        }

        $articles = $query->paginate(6);

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        // Increment views
        $article->increment('views');

        // Get related articles
        $relatedArticles = Article::where('is_active', true)
            ->where('id', '!=', $article->id)
            ->orderBy('published_date', 'desc')
            ->take(4)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
