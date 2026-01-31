<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
   //display a list of articles
    public function index()
    {
        return response()->json(
            Article::latest()->get()
        );
    }

    //store article
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'required|image|max:4096',
            'status'           => 'required|boolean',
            'published_at'     => $request->boolean('status')
                                    ? 'required|date'
                                    : 'nullable|date',
        ]);

        // Upload image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('articles', 'public');
        }

        $article = Article::create($validated);

        return response()->json([
            'message' => 'Article created successfully',
            'data'    => $article,
        ], 201);
    }

   //shows article 
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return response()->json($article);
    }

   //update an article
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|max:4096',
            'status'           => 'required|boolean',
            'published_at'     => $request->boolean('status')
                                    ? 'required|date'
                                    : 'nullable|date',
        ]);

        // Replace image if new one uploaded
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }

            $validated['featured_image'] = $request->file('featured_image')
                ->store('articles', 'public');
        }

        $article->update($validated);

        return response()->json([
            'message' => 'Article updated successfully',
            'data'    => $article,
        ]);
    }

  
     // Delete an article
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return response()->json([
            'message' => 'Article deleted successfully',
        ]);
    }
}
