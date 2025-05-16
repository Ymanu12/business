<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController extends Controller
{
    public function index()
    {
        // Vérifie s'il y a des posts avant de tenter d'accéder au premier
        $latestPost = Post::published()->latest()->first();

        // Si aucun post n'existe, retourne une vue avec des données vides
        if (!$latestPost) {
            $emptyPaginator = new LengthAwarePaginator([], 0, 6);
            return view('blogs', [
                'latestPost' => null,
                'posts' => $emptyPaginator,
                'recentPosts' => collect()
            ]);
        }

        // Récupère les posts (excluant le dernier post) avec pagination
        $posts = Post::published()
                ->where('id', '!=', $latestPost->id)
                ->latest()
                ->paginate(10);

        // Récupère les posts récents pour la sidebar
        $recentPosts = Post::published()
                        ->where('id', '!=', $latestPost->id)
                        ->latest()
                        ->take(4)
                        ->get();

        return view('blogs', compact('latestPost', 'posts', 'recentPosts'));
    }

    public function show(Post $post)
    {
        // Vérifie si l'article est publié
        if (method_exists($post, 'isPublished') && !$post->isPublished()) {
            abort(404);
        }

        // Incrémente les vues sans modifier les timestamps
        $post->timestamps = false;
        $post->increment('views');
        $post->timestamps = true;

        // Récupère les commentaires (facultatif) et leur nombre
        $post->loadCount('comments');

        // Articles précédent et suivant
        $previous = Post::where('id', '<', $post->id)->orderBy('id', 'desc')->first();
        $next = Post::where('id', '>', $post->id)->orderBy('id')->first();

        return view('blog-show', compact('post', 'previous', 'next'));
    }

    public function blogs()
    {
        return $this->index();
    }
}
