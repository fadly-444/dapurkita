<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Recipe::with(['user', 'category', 'ratings']);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $recipes    = $query->latest()->paginate(9);
        $categories = Category::all();

        return view('recipes.index', compact('recipes', 'categories'));
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'category', 'ingredients', 'ratings', 'comments.user']);
        $userRating = Auth::check()
            ? $recipe->ratings()->where('user_id', Auth::id())->first()
            : null;

        return view('recipes.show', compact('recipe', 'userRating'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('recipes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'                      => 'required|max:255',
            'category_id'                => 'required|exists:categories,id',
            'description'                => 'required',
            'instructions'               => 'required',
            'cook_time'                  => 'required|integer|min:1',
            'servings'                   => 'required|integer|min:1',
            'image'                      => 'nullable|image|max:2048',
            'ingredients'                => 'required|array|min:1',
            'ingredients.*.name'         => 'required|string',
            'ingredients.*.quantity'     => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe = Recipe::create([
            'user_id'      => Auth::id(),
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'description'  => $request->description,
            'instructions' => $request->instructions,
            'cook_time'    => $request->cook_time,
            'servings'     => $request->servings,
            'image'        => $imagePath,
        ]);

        foreach ($request->ingredients as $ing) {
            $recipe->ingredients()->create([
                'name'     => $ing['name'],
                'quantity' => $ing['quantity'],
            ]);
        }

        return redirect()->route('recipes.show', $recipe)->with('success', 'Resep berhasil ditambahkan!');
    }

    public function edit(Recipe $recipe)
    {
        if (Auth::id() !== $recipe->user_id) {
            abort(403);
        }
        $categories = Category::all();
        return view('recipes.create', compact('recipe', 'categories'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        if (Auth::id() !== $recipe->user_id) {
            abort(403);
        }

        $request->validate([
            'title'        => 'required|max:255',
            'category_id'  => 'required|exists:categories,id',
            'description'  => 'required',
            'instructions' => 'required',
            'cook_time'    => 'required|integer|min:1',
            'servings'     => 'required|integer|min:1',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'category_id', 'description', 'instructions', 'cook_time', 'servings']);

        if ($request->hasFile('image')) {
            if ($recipe->image) Storage::disk('public')->delete($recipe->image);
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update($data);

        if ($request->ingredients) {
            $recipe->ingredients()->delete();
            foreach ($request->ingredients as $ing) {
                $recipe->ingredients()->create($ing);
            }
        }

        return redirect()->route('recipes.show', $recipe)->with('success', 'Resep berhasil diperbarui!');
    }

    public function destroy(Recipe $recipe)
    {
        if (Auth::id() !== $recipe->user_id) {
            abort(403);
        }
        if ($recipe->image) Storage::disk('public')->delete($recipe->image);
        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', 'Resep berhasil dihapus.');
    }

    public function rate(Request $request, Recipe $recipe)
    {
        $request->validate(['score' => 'required|integer|min:1|max:5']);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'recipe_id' => $recipe->id],
            ['score' => $request->score]
        );

        return back()->with('success', 'Rating berhasil disimpan!');
    }

    public function comment(Request $request, Recipe $recipe)
    {
        $request->validate(['body' => 'required|max:1000']);

        $recipe->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}