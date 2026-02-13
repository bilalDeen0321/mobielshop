import { Heart, ShoppingCart, Star } from "lucide-react";
import { toast } from "sonner";
import { useStore } from "@/context/StoreContext";
import type { Product } from "@/data/products";

type ProductCardProps = Omit<Product, "condition" | "inStock" | "id"> & {
  id: number;
};

const ProductCard = ({
  id,
  name,
  brand,
  price,
  originalPrice,
  image,
  rating,
  badge,
}: ProductCardProps) => {
  const discount = originalPrice ? Math.round(((originalPrice - price) / originalPrice) * 100) : 0;
  const { addToCart, toggleWishlist, isInWishlist } = useStore();
  const wished = isInWishlist(id);

  const handleAddToCart = () => {
    // Build a minimal Product object for the store from props
    const product: Product = {
      id,
      name,
      brand,
      category: "Mobile Phones",
      price,
      originalPrice,
      image,
      rating,
      badge,
      condition: "New",
      inStock: true,
    };
    addToCart(product, 1);
    toast.success("Product added to cart successfully");
  };

  const handleToggleWishlist = () => {
    toggleWishlist(id);
  };

  return (
    <div className="bg-card rounded-lg border border-border overflow-hidden product-card-hover group">
      {/* Image */}
      <div className="relative aspect-square bg-secondary p-4 flex items-center justify-center overflow-hidden">
        <img
          src={image}
          alt={name}
          className="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
        />
        {badge && (
          <span className="absolute top-3 left-3 bg-sale text-sale-foreground text-[10px] font-bold px-2 py-1 rounded">
            {badge}
          </span>
        )}
        {discount > 0 && (
          <span className="absolute top-3 right-3 bg-accent text-accent-foreground text-[10px] font-bold px-2 py-1 rounded">
            -{discount}%
          </span>
        )}
        {/* Hover actions */}
        <div className="absolute bottom-3 left-3 right-3 flex justify-center gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
          <button
            type="button"
            onClick={handleToggleWishlist}
            className="h-9 w-9 rounded-full bg-card border border-border flex items-center justify-center hover:bg-primary hover:text-primary-foreground hover:border-primary transition-colors shadow-sm"
          >
            <Heart
              className={`h-4 w-4 ${
                wished ? "fill-red-500 text-red-500" : ""
              }`}
            />
          </button>
          <button
            type="button"
            onClick={handleAddToCart}
            className="h-9 px-4 rounded-full bg-primary text-primary-foreground flex items-center gap-1.5 text-xs font-semibold hover:opacity-90 transition-opacity shadow-sm"
          >
            <ShoppingCart className="h-3.5 w-3.5" />
            Add to Cart
          </button>
        </div>
      </div>

      {/* Info */}
      <div className="p-4">
        <p className="text-xs text-muted-foreground mb-1">{brand}</p>
        <h3 className="text-sm font-semibold text-foreground line-clamp-2 mb-2 min-h-[2.5rem]">
          {name}
        </h3>
        <div className="flex items-center gap-1 mb-2">
          {Array.from({ length: 5 }).map((_, i) => (
            <Star
              key={i}
              className={`h-3 w-3 ${i < rating ? "fill-accent text-accent" : "text-border"}`}
            />
          ))}
          <span className="text-[10px] text-muted-foreground ml-1">({rating}.0)</span>
        </div>
        <div className="flex items-baseline gap-2">
          <span className="text-lg font-bold text-primary">£{price.toFixed(2)}</span>
          {originalPrice && (
            <span className="text-sm text-muted-foreground line-through">
              £{originalPrice.toFixed(2)}
            </span>
          )}
        </div>
      </div>
    </div>
  );
};

export default ProductCard;

