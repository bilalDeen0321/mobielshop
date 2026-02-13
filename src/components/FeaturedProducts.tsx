import ProductCard from "./ProductCard";
import { allProducts } from "@/data/products";

const products = allProducts.slice(0, 8);

const FeaturedProducts = () => (
  <section id="products" className="py-10">
    <div className="container">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-2xl font-display font-bold text-foreground">Featured Products</h2>
        <a href="/collections/all" className="text-sm font-medium text-primary hover:underline">View All →</a>
      </div>
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {products.map((product) => (
          <ProductCard key={product.name} {...product} />
        ))}
      </div>
    </div>
  </section>
);

export default FeaturedProducts;
