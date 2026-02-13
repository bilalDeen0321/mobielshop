import { useEffect, useMemo, useState } from "react";
import { useParams, useSearchParams } from "react-router-dom";
import PageShell from "./PageShell";
import { ChevronDown, ChevronUp, SlidersHorizontal } from "lucide-react";
import ProductCard from "@/components/ProductCard";
import type { Product } from "@/data/products";
import { apiFetch } from "@/lib/api";
import heroBanner from "@/assets/hero-banner.jpg";

type SortOption = "az" | "za" | "price-asc" | "price-desc" | "newest";

const sortLabels: Record<SortOption, string> = {
  az: "Alphabetically, A-Z",
  za: "Alphabetically, Z-A",
  "price-asc": "Price, low to high",
  "price-desc": "Price, high to low",
  newest: "Date, new to old",
};

const FilterSection = ({
  title,
  children,
  defaultOpen = true,
}: {
  title: string;
  children: React.ReactNode;
  defaultOpen?: boolean;
}) => {
  const [open, setOpen] = useState(defaultOpen);
  return (
    <div className="border-b border-border py-3">
      <button
        onClick={() => setOpen(!open)}
        className="flex items-center justify-between w-full text-sm font-semibold text-foreground"
      >
        {title}
        {open ? <ChevronUp className="h-4 w-4" /> : <ChevronDown className="h-4 w-4" />}
      </button>
      {open && <div className="mt-3">{children}</div>}
    </div>
  );
};

const Collections = () => {
  const { brand } = useParams<{ brand?: string }>();
  const [searchParams] = useSearchParams();
  const [sort, setSort] = useState<SortOption>("az");
  const [showSortDropdown, setShowSortDropdown] = useState(false);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [selectedConditions, setSelectedConditions] = useState<string[]>([]);
  const [availabilityFilter, setAvailabilityFilter] = useState<string[]>([]);
  const [showMobileFilters, setShowMobileFilters] = useState(false);
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(false);

  const brands = useMemo(() => [...new Set(products.map((p) => p.brand))].sort(), [products]);
  const categories = useMemo(
    () => [...new Set(products.map((p) => p.category))].sort(),
    [products],
  );
  const conditions = useMemo(
    () => [...new Set(products.map((p) => p.condition))].sort(),
    [products],
  );

  const toggleFilter = (arr: string[], val: string, setter: (v: string[]) => void) => {
    setter(arr.includes(val) ? arr.filter((v) => v !== val) : [...arr, val]);
  };

  useEffect(() => {
    const load = async () => {
      setLoading(true);
      try {
        const params = new URLSearchParams();
        const brandFilter = brand ?? searchParams.get("brand") ?? undefined;
        const categoryFilter = searchParams.get("category") ?? undefined;
        const saleOnly = searchParams.get("sale") === "true";

        if (brandFilter) params.set("brand", brandFilter);
        if (categoryFilter) params.set("category", categoryFilter);
        if (saleOnly) {
          // simple approach: just sort by price; sale logic still handled in frontend
        }

        const res = await apiFetch(`/api/products?limit=100&${params.toString()}`);
        const data = await res.json();
        if (!res.ok) {
          console.error(data);
          return;
        }

        const mapped: Product[] = data.data.map((p: any) => ({
          id: p.id,
          name: p.name,
          brand: p.brand || "",
          category: p.category_name || "Mobile Phones",
          price: Number(p.min_price ?? p.base_price ?? 0),
          originalPrice:
            p.max_price && p.max_price > (p.min_price ?? p.base_price ?? 0)
              ? Number(p.max_price)
              : undefined,
          image: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop",
          rating: 4,
          badge: undefined,
          condition: (p.condition as Product["condition"]) || "New",
          inStock: (p.total_stock ?? 0) > 0,
        }));
        setProducts(mapped);
      } catch (err) {
        console.error("Failed to load products", err);
      } finally {
        setLoading(false);
      }
    };
    load();
  }, [brand, searchParams]);

  const filtered = useMemo(() => {
    let result = [...products];

    const saleOnly = searchParams.get("sale") === "true";

    if (saleOnly) {
      result = result.filter((p) => p.originalPrice && p.originalPrice > p.price);
    }
    if (selectedBrands.length) result = result.filter((p) => selectedBrands.includes(p.brand));
    if (selectedCategories.length) result = result.filter((p) => selectedCategories.includes(p.category));
    if (selectedConditions.length) result = result.filter((p) => selectedConditions.includes(p.condition));
    if (availabilityFilter.length) {
      result = result.filter((p) => {
        if (availabilityFilter.includes("In stock") && p.inStock) return true;
        if (availabilityFilter.includes("Out of stock") && !p.inStock) return true;
        return false;
      });
    }

    switch (sort) {
      case "az": result.sort((a, b) => a.name.localeCompare(b.name)); break;
      case "za": result.sort((a, b) => b.name.localeCompare(a.name)); break;
      case "price-asc": result.sort((a, b) => a.price - b.price); break;
      case "price-desc": result.sort((a, b) => b.price - a.price); break;
      default: break;
    }
    return result;
  }, [sort, selectedBrands, selectedCategories, selectedConditions, availabilityFilter]);

  const filterSidebar = (
    <div className="space-y-0">
      <div className="border-b border-border pb-3 mb-1">
        <h3 className="text-sm font-bold text-foreground flex items-center gap-2">
          <SlidersHorizontal className="h-4 w-4" /> Filter:
        </h3>
      </div>

      <FilterSection title="Availability">
        {["In stock", "Out of stock"].map((v) => (
          <label key={v} className="flex items-center gap-2 text-sm text-foreground mb-2 cursor-pointer">
            <input
              type="checkbox"
              checked={availabilityFilter.includes(v)}
              onChange={() => toggleFilter(availabilityFilter, v, setAvailabilityFilter)}
              className="rounded border-border text-primary focus:ring-primary"
            />
            {v} ({products.filter((p) => (v === "In stock" ? p.inStock : !p.inStock)).length})
          </label>
        ))}
      </FilterSection>

      <FilterSection title="Brand">
        {brands.map((b) => (
          <label key={b} className="flex items-center gap-2 text-sm text-foreground mb-2 cursor-pointer">
            <input
              type="checkbox"
              checked={selectedBrands.includes(b)}
              onChange={() => toggleFilter(selectedBrands, b, setSelectedBrands)}
              className="rounded border-border text-primary focus:ring-primary"
            />
            {b} ({products.filter((p) => p.brand === b).length})
          </label>
        ))}
      </FilterSection>

      <FilterSection title="Category">
        {categories.map((c) => (
          <label key={c} className="flex items-center gap-2 text-sm text-foreground mb-2 cursor-pointer">
            <input
              type="checkbox"
              checked={selectedCategories.includes(c)}
              onChange={() => toggleFilter(selectedCategories, c, setSelectedCategories)}
              className="rounded border-border text-primary focus:ring-primary"
            />
            {c} ({products.filter((p) => p.category === c).length})
          </label>
        ))}
      </FilterSection>

      <FilterSection title="Condition">
        {conditions.map((c) => (
          <label key={c} className="flex items-center gap-2 text-sm text-foreground mb-2 cursor-pointer">
            <input
              type="checkbox"
              checked={selectedConditions.includes(c)}
              onChange={() => toggleFilter(selectedConditions, c, setSelectedConditions)}
              className="rounded border-border text-primary focus:ring-primary"
            />
            {c} ({products.filter((p) => p.condition === c).length})
          </label>
        ))}
      </FilterSection>
    </div>
  );

  return (
    <PageShell>
      {/* Banner */}
      <div
        className="relative h-40 md:h-52 bg-cover bg-center flex items-center justify-center"
        style={{ backgroundImage: `url(${heroBanner})` }}
      >
        <div className="absolute inset-0 bg-foreground/40" />
        <h1 className="relative text-3xl md:text-4xl font-display font-bold text-primary-foreground z-10">
          {brand ? `${brand} Phones` : "Products"}
        </h1>
      </div>

      <div className="container py-8">
        {/* Mobile filter toggle */}
        <button
          onClick={() => setShowMobileFilters(!showMobileFilters)}
          className="md:hidden flex items-center gap-2 mb-4 text-sm font-semibold text-primary border border-primary px-4 py-2 rounded-lg"
        >
          <SlidersHorizontal className="h-4 w-4" />
          {showMobileFilters ? "Hide Filters" : "Show Filters"}
        </button>

        <div className="flex gap-8">
          {/* Sidebar - desktop */}
          <aside className="hidden md:block w-60 shrink-0">{filterSidebar}</aside>

          {/* Mobile filters */}
          {showMobileFilters && <aside className="md:hidden w-full mb-4">{filterSidebar}</aside>}

          {/* Products */}
          <div className="flex-1">
            {/* Sort bar */}
            <div className="flex items-center justify-end gap-4 mb-6">
              <div className="relative">
                <button
                  onClick={() => setShowSortDropdown(!showSortDropdown)}
                  className="flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground"
                >
                  Sort by: <span className="font-medium text-foreground">{sortLabels[sort]}</span>
                  <ChevronDown className="h-3.5 w-3.5" />
                </button>
                {showSortDropdown && (
                  <div className="absolute right-0 top-full mt-1 bg-card border border-border rounded-lg shadow-lg z-20 min-w-[200px]">
                    {(Object.keys(sortLabels) as SortOption[]).map((key) => (
                      <button
                        key={key}
                        onClick={() => { setSort(key); setShowSortDropdown(false); }}
                        className={`block w-full text-left px-4 py-2 text-sm hover:bg-secondary ${sort === key ? "text-primary font-medium" : "text-foreground"}`}
                      >
                        {sortLabels[key]}
                      </button>
                    ))}
                  </div>
                )}
              </div>
              <span className="text-sm text-muted-foreground">
                {loading ? "Loading..." : `${filtered.length} products`}
              </span>
            </div>

            {/* Grid */}
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              {filtered.map((product) => (
                <ProductCard key={product.id} {...product} />
              ))}
            </div>

            {filtered.length === 0 && (
              <div className="text-center py-16">
                <p className="text-lg text-muted-foreground">No products match your filters.</p>
              </div>
            )}
          </div>
        </div>
      </div>

    </PageShell>
  );
};

export default Collections;
