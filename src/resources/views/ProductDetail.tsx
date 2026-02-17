import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import PageShell from "./PageShell";
import { apiFetch } from "@/lib/api";
import { ShoppingCart } from "lucide-react";
import { useStore } from "@/context/StoreContext";
import type { Product } from "@/data/products";

interface BackendVariant {
  id: number;
  sku: string;
  variant_name: string | null;
  price: number;
  stock: number;
}

const ProductDetail = () => {
  const { slug } = useParams<{ slug: string }>();
  const [product, setProduct] = useState<Product | null>(null);
  const [variants, setVariants] = useState<BackendVariant[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const { addToCart } = useStore();

  useEffect(() => {
    if (!slug) return;
    const load = async () => {
      setLoading(true);
      setError(null);
      try {
        const res = await apiFetch(`/api/products/${slug}`);
        const data = await res.json();
        if (!res.ok) {
          setError(data.message || "Failed to load product");
          return;
        }

        const p = data.product;
        const mapped: Product = {
          id: p.id,
          name: p.name,
          brand: p.brand || "",
          category: data.product.category_name || "Mobile Phones",
          price: Number(p.base_price ?? 0),
          originalPrice: undefined,
          image:
            "https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&h=600&fit=crop",
          rating: 4,
          badge: undefined,
          condition: (p.condition as Product["condition"]) || "New",
          inStock: true,
        };
        setProduct(mapped);
        setVariants(
          (data.variants as BackendVariant[] | undefined) ?? [],
        );
      } catch (err) {
        console.error("Product detail error", err);
        setError("Failed to load product");
      } finally {
        setLoading(false);
      }
    };
    load();
  }, [slug]);

  const handleAddToCart = () => {
    if (!product) return;
    addToCart(product, 1);
  };

  return (
    <PageShell>
      <section className="container py-10">
        {loading && <p className="text-sm text-muted-foreground">Loading...</p>}
        {error && (
          <p className="text-sm text-red-500">
            {error}
          </p>
        )}
        {!loading && !error && product && (
          <div className="grid gap-8 md:grid-cols-[1.2fr,1fr]">
            <div className="bg-secondary rounded-lg flex items-center justify-center p-6">
              <img
                src={product.image}
                alt={product.name}
                className="max-h-[380px] w-auto object-contain"
              />
            </div>
            <div>
              <p className="text-xs text-muted-foreground mb-1">
                {product.brand}
              </p>
              <h1 className="text-2xl md:text-3xl font-display font-bold text-foreground mb-2">
                {product.name}
              </h1>
              <p className="text-sm text-muted-foreground mb-4">
                Condition: {product.condition}
              </p>
              <div className="flex items-baseline gap-3 mb-6">
                <span className="text-2xl font-bold text-primary">
                  £{product.price.toFixed(2)}
                </span>
                {product.originalPrice && (
                  <span className="text-sm text-muted-foreground line-through">
                    £{product.originalPrice.toFixed(2)}
                  </span>
                )}
              </div>

              <button
                type="button"
                onClick={handleAddToCart}
                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity mb-6"
              >
                <ShoppingCart className="h-4 w-4" />
                Add to Cart
              </button>

              {variants.length > 0 && (
                <div className="mt-4">
                  <h2 className="text-sm font-semibold text-foreground mb-2">
                    Available variants
                  </h2>
                  <ul className="space-y-1 text-xs text-muted-foreground">
                    {variants.map((v) => (
                      <li key={v.id}>
                        {v.variant_name || v.sku} — £
                        {Number(v.price).toFixed(2)} (
                        {v.stock > 0 ? `${v.stock} in stock` : "Out of stock"})
                      </li>
                    ))}
                  </ul>
                </div>
              )}
            </div>
          </div>
        )}
      </section>
    </PageShell>
  );
};

export default ProductDetail;
