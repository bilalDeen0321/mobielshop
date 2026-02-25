<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'brand' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string'],
            'payment_info' => ['nullable', 'string'],
            'shipping_info' => ['nullable', 'string'],
            'returns_info' => ['nullable', 'string'],
            'warranty_info' => ['nullable', 'string'],
            'other_policies' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $product = Product::create($data);

        if ($request->hasFile('images')) {
            $sortOrder = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $path = $file->store('products/' . $product->id, 'public');
                $product->images()->create([
                    'url' => 'storage/' . $path,
                    'alt' => $product->name,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Product created successfully.']);
        }
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Request $request, Product $product)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $product->load('images');
            $product->images->each(function ($img) {
                $img->full_url = asset($img->url);
            });
            return response()->json([
                'product' => $product,
                'categories' => Category::orderBy('name')->get(),
            ]);
        }
        return redirect()->route('admin.products.index');
    }

    public function edit(Request $request, Product $product)
    {
        $categories = Category::orderBy('name')->get();
        if ($request->ajax() || $request->wantsJson()) {
            $product->load('images');
            $product->images->each(function ($img) {
                $img->full_url = asset($img->url);
            });
            return response()->json([
                'product' => $product,
                'categories' => $categories,
            ]);
        }
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'base_price' => ['required', 'numeric', 'min:0'],
            'brand' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string'],
            'payment_info' => ['nullable', 'string'],
            'shipping_info' => ['nullable', 'string'],
            'returns_info' => ['nullable', 'string'],
            'warranty_info' => ['nullable', 'string'],
            'other_policies' => ['nullable', 'string'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $product->update($data);

        if ($request->filled('delete_image_ids')) {
            $imagesToDelete = $product->images()->whereIn('id', $request->delete_image_ids)->get();
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete(str_replace('storage/', '', $img->url));
                $img->delete();
            }
        }

        if ($request->hasFile('images')) {
            $sortOrder = (int) $product->images()->max('sort_order') + 1;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $path = $file->store('products/' . $product->id, 'public');
                $product->images()->create([
                    'url' => 'storage/' . $path,
                    'alt' => $product->name,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
        }
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
