<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOptionDefinition;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'brandRelation');

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%')
                    ->orWhere('brand', 'like', '%' . $q . '%')
                    ->orWhereHas('category', function ($c) use ($q) {
                        $c->where('name', 'like', '%' . $q . '%');
                    });
            });
        }

        $sort = $request->get('sort', 'id');
        $dir = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSort = ['id', 'name', 'category', 'brand', 'wholesale_price', 'retail_price', 'profit', 'stock_quantity'];
        if (in_array($sort, $allowedSort)) {
            switch ($sort) {
                case 'name':
                    $query->orderBy('name', $dir);
                    break;
                case 'category':
                    $query->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                        ->orderBy('categories.name', $dir)
                        ->select('products.*');
                    break;
                case 'brand':
                    $query->orderBy('brand', $dir);
                    break;
                case 'wholesale_price':
                    $query->orderBy('wholesale_price', $dir);
                    break;
                case 'retail_price':
                    $query->orderBy('retail_price', $dir);
                    break;
                case 'profit':
                    $query->orderByRaw('(products.retail_price - products.wholesale_price) ' . $dir);
                    break;
                case 'stock_quantity':
                    $query->orderBy('stock_quantity', $dir);
                    break;
                default:
                    $query->orderBy('products.id', $dir);
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'wholesale_price' => ['required', 'numeric', 'min:0'],
            'retail_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'minimum_stock_limit' => ['nullable', 'integer', 'min:0'],
            'is_on_sale' => ['boolean'],
            'sale_discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'condition' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string'],
            'payment_info' => ['nullable', 'string'],
            'shipping_info' => ['nullable', 'string'],
            'returns_info' => ['nullable', 'string'],
            'warranty_info' => ['nullable', 'string'],
            'other_policies' => ['nullable', 'string'],
            'option_definitions' => ['nullable', 'array'],
            'option_definitions.*.key' => ['required', 'string', 'in:color,storage,size,condition'],
            'option_definitions.*.label' => ['nullable', 'string', 'max:100'],
            'option_definitions.*.values' => ['nullable', 'array'],
            'option_definitions.*.values.*' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['show_color'] = $request->boolean('show_color');
        $data['show_storage'] = $request->boolean('show_storage');
        $data['show_condition'] = $request->boolean('show_condition');
        $data['base_price'] = (float) $data['retail_price'];
        $data['stock_quantity'] = (int) ($data['stock_quantity'] ?? 0);
        $data['minimum_stock_limit'] = (int) ($data['minimum_stock_limit'] ?? 0);
        $data['is_on_sale'] = $request->boolean('is_on_sale');
        $data['sale_discount_percent'] = $request->filled('is_on_sale') && $request->filled('sale_discount_percent') ? (float) $request->sale_discount_percent : null;
        $data['brand'] = Brand::find($data['brand_id'] ?? null)?->name;
        $product = Product::create($data);
        $this->syncOptionDefinitions($product, $request->input('option_definitions', []));

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
            $product->load(['images', 'optionDefinitions.values']);
            $product->images->each(function ($img) {
                $img->full_url = asset($img->url);
            });
            $product->option_definitions = $product->optionDefinitions->map(function ($def) {
                return [
                    'option_key' => $def->option_key,
                    'option_label' => $def->option_label,
                    'values' => $def->values->map(fn ($v) => ['value' => $v->value])->all(),
                ];
            })->all();
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
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'wholesale_price' => ['required', 'numeric', 'min:0'],
            'retail_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'minimum_stock_limit' => ['nullable', 'integer', 'min:0'],
            'is_on_sale' => ['boolean'],
            'sale_discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'condition' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string'],
            'payment_info' => ['nullable', 'string'],
            'shipping_info' => ['nullable', 'string'],
            'returns_info' => ['nullable', 'string'],
            'warranty_info' => ['nullable', 'string'],
            'other_policies' => ['nullable', 'string'],
            'option_definitions' => ['nullable', 'array'],
            'option_definitions.*.key' => ['required', 'string', 'in:color,storage,size,condition'],
            'option_definitions.*.label' => ['nullable', 'string', 'max:100'],
            'option_definitions.*.values' => ['nullable', 'array'],
            'option_definitions.*.values.*' => ['nullable', 'string', 'max:255'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['base_price'] = (float) $data['retail_price'];
        $data['stock_quantity'] = (int) ($data['stock_quantity'] ?? 0);
        $data['minimum_stock_limit'] = (int) ($data['minimum_stock_limit'] ?? 0);
        $data['is_on_sale'] = $request->boolean('is_on_sale');
        $data['sale_discount_percent'] = $request->filled('is_on_sale') && $request->filled('sale_discount_percent') ? (float) $request->sale_discount_percent : null;
        $data['brand'] = Brand::find($data['brand_id'] ?? null)?->name;
        $product->update($data);
        $this->syncOptionDefinitions($product, $request->input('option_definitions', []));

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

    public function updateStatus(Request $request, Product $product)
    {
        $product->is_active = $request->boolean('is_active');
        $product->save();
        return response()->json(['success' => true, 'is_active' => (bool) $product->is_active]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function syncOptionDefinitions(Product $product, array $definitions): void
    {
        $product->optionDefinitions()->delete();

        $sortOrder = 0;
        foreach ($definitions as $def) {
            $key = $def['key'] ?? null;
            $label = $def['label'] ?? ucfirst($key);
            $values = $def['values'] ?? [];
            if (! $key || ! is_array($values)) {
                continue;
            }
            $values = array_values(array_filter(array_map('trim', $values)));
            if (empty($values)) {
                continue;
            }
            $definition = $product->optionDefinitions()->create([
                'option_key' => $key,
                'option_label' => $label,
                'sort_order' => $sortOrder++,
            ]);
            foreach ($values as $i => $value) {
                if ((string) $value === '') {
                    continue;
                }
                $definition->values()->create([
                    'value' => $value,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
