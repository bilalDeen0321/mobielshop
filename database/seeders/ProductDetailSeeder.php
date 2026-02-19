<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\RecentlyViewed;
use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    protected array $placeholderImages = [
        'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1592286927505-d6d2a4d0e2d2?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=800&h=600&fit=crop',
    ];

    protected string $defaultDescription = "**Product details**\n\nCondition: As stated. Full description and specifications.\n\n**What's included?**\n✅ Device\n✅ Charger & cable\n✅ 12 Months Warranty";

    protected string $paymentInfo = "We accept all major credit and debit cards. All payments are processed securely. Handling time: Orders dispatched within 1 working day.";

    protected string $shippingInfo = "**Delivery options:**\n• Standard (1–2 working days): £5.95 (orders under £199)\n• Free delivery on orders £200+\n• Next Day (by 1pm): £9.95\nAll UK orders sent via tracked service.";

    protected string $returnsInfo = "30-day return policy. Item must be unused in original packaging. Buyer responsible for return postage. Processing within 48 hours of receipt.";

    protected string $warrantyInfo = "12-month seller warranty. Within 30 days: return if faulty. After 30 days: repair or replacement. Damage from misuse not covered.";

    protected string $otherPolicies = "IMEI/serial numbers recorded for security. Fraud prevention in place. Devices sold UK/EU stock only.";

    public function run(): void
    {
        $products = Product::with('variants')->get();
        $categories = Category::all()->keyBy('id');

        foreach ($products as $index => $product) {
            $product->update([
                'description' => $this->defaultDescription,
                'payment_info' => $this->paymentInfo,
                'shipping_info' => $this->shippingInfo,
                'returns_info' => $this->returnsInfo,
                'warranty_info' => $this->warrantyInfo,
                'other_policies' => $this->otherPolicies,
            ]);

            foreach ($this->placeholderImages as $i => $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $url,
                    'alt' => $product->name . ' – image ' . ($i + 1),
                    'sort_order' => $i,
                ]);
            }

            $firstVariant = $product->variants->first();
            if ($firstVariant) {
                $firstVariant->update([
                    'color' => $product->brand === 'Apple' ? 'Black' : 'Default',
                    'storage' => '128GB',
                    'condition' => $product->condition,
                ]);
            }

            if ($product->brand === 'Apple' && $product->slug === 'iphone-15-pro-max-256gb') {
                foreach ([
                    ['color' => 'Black', 'storage' => '256GB', 'condition' => 'Refurbished', 'price' => 899.99],
                    ['color' => 'Blue', 'storage' => '256GB', 'condition' => 'Refurbished', 'price' => 919.99],
                    ['color' => 'Natural', 'storage' => '512GB', 'condition' => 'Refurbished', 'price' => 999.99],
                ] as $i => $opt) {
                    $v = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => strtoupper(substr(md5($product->slug . $i), 0, 8)),
                        'variant_name' => $opt['color'] . ' / ' . $opt['storage'] . ' / ' . $opt['condition'],
                        'price' => $opt['price'],
                        'color' => $opt['color'],
                        'storage' => $opt['storage'],
                        'condition' => $opt['condition'],
                    ]);
                    Inventory::create(['variant_id' => $v->id, 'quantity' => 5]);
                }
            }
        }

        $productIds = $products->pluck('id')->all();
        $sessionId = 'seed-recently-viewed';
        foreach (array_slice(array_reverse($productIds), 0, 5) as $i => $pid) {
            RecentlyViewed::create([
                'session_id' => $sessionId,
                'product_id' => $pid,
                'viewed_at' => now()->subMinutes($i * 10),
            ]);
        }
    }
}
