import { Smartphone } from "lucide-react";

const brands = [
  { name: "Apple", count: 24 },
  { name: "Samsung", count: 31 },
  { name: "Google", count: 12 },
  { name: "Huawei", count: 18 },
  { name: "OnePlus", count: 9 },
  { name: "Sony", count: 7 },
];

const BrandCategories = () => (
  <section className="py-10 bg-secondary">
    <div className="container">
      <h2 className="text-2xl font-display font-bold text-foreground mb-6">Shop By Brand</h2>
      <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
        {brands.map((brand) => (
          <a
            key={brand.name}
            href="#"
            className="bg-card rounded-lg p-5 flex flex-col items-center gap-3 border border-border product-card-hover group"
          >
            <div className="w-14 h-14 rounded-full bg-secondary flex items-center justify-center group-hover:bg-primary/10 transition-colors">
              <Smartphone className="h-6 w-6 text-primary" />
            </div>
            <div className="text-center">
              <p className="font-semibold text-foreground text-sm">{brand.name}</p>
              <p className="text-xs text-muted-foreground">{brand.count} products</p>
            </div>
          </a>
        ))}
      </div>
    </div>
  </section>
);

export default BrandCategories;
