import { useEffect, useMemo, useState } from "react";
import { useSearchParams } from "react-router-dom";
import { Search, ChevronDown, ChevronUp, SlidersHorizontal, X } from "lucide-react";
import PageShell from "./PageShell";
import ProductCard from "@/components/ProductCard";
import type { Product } from "@/data/products";
import { apiFetch } from "@/lib/api";
import heroBanner from "@/assets/hero-banner.jpg";

type SortOption = "relevance" | "az" | "za" | "price-asc" | "price-desc";

const sortLabels: Record<SortOption, string> = {
  relevance: "Relevance",
  az: "Alphabetically, A-Z",
  za: "Alphabetically, Z-A",
  "price-asc": "Price, low to high",
  "price-desc": "Price, high to low",
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

const SearchResults = () => {
  const [searchParams, setSearchParams] = useSearchParams();
  const queryParam = searchParams.get("q") || "";
  const [localQuery, setLocalQuery] = useState(queryParam);
  const [sort, setSort] = useState<SortOption>("relevance");
  const [showSortDropdown, setShowSortDropdown] = useState(false);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);
  const [availabilityFilter, setAvailabilityFilter] = useState<string[]>([]);
  const [showMobileFilters, setShowMobileFilters] = useState(false);
  const [maxPrice, setMaxPrice] = useState<number | null>(null);
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(false);

  const absoluteMaxPrice = useMemo(
    () => (products.length ? Math.max(...products.map((p) => p.price)) : 0),
    [products],
  );

  const toggleFilter = (arr: string[], val: string, setter: (v: string[]) => void) => {
    setter(arr.includes(val) ? arr.filter((v) => v !== val) : [...arr, val]);
  };

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setSearchParams({ q: localQuery });
  };

  useEffect(() => {
    if (!queryParam) {
      setProducts([]);
      return;
    }
    const load = async () => {
      setLoading(true);
      try {
        const params = new URLSearchParams({
          q: queryParam,
          limit: "100",
        });
        const res = await apiFetch(`/api/products?${params.toString()}`);
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
        if (mapped.length && maxPrice === null) {
          const max = Math.max(...mapped.map((p) => p.price));
          setMaxPrice(max);
        }
      } catch (err) {
        console.error("Search load error", err);
      } finally {
        setLoading(false);
      }
    };
    load();
  }, [queryParam]);

  const results = useMemo(() => {
    let result = [...products];

    if (selectedBrands.length) result = result.filter((p) => selectedBrands.includes(p.brand));
    if (availabilityFilter.length) {
      result = result.filter((p) => {
        if (availabilityFilter.includes("In stock") && p.inStock) return true;
        if (availabilityFilter.includes("Out of stock") && !p.inStock) return true;
        return false;
      });
    }
    if (maxPrice !== null) {
      result = result.filter((p) => p.price <= maxPrice);
    }

    switch (sort) {
      case "az": result.sort((a, b) => a.name.localeCompare(b.name)); break;
      case "za": result.sort((a, b) => b.name.localeCompare(a.name)); break;
      case "price-asc": result.sort((a, b) => a.price - b.price); break;
      case "price-desc": result.sort((a, b) => b.price - a.price); break;
      default: break;
    }
    return result;
  }, [products, sort, selectedBrands, availabilityFilter, maxPrice]);

  const resultBrands = useMemo(
    () => [...new Set(products.map((p) => p.brand))].sort(),
    [products],
  );

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
            {v}
          </label>
        ))}
      </FilterSection>

      <FilterSection title="Price">
        <div className="space-y-2">
          <input
            type="range"
            min={0}
            max={absoluteMaxPrice}
            value={maxPrice ?? absoluteMaxPrice}
            onChange={(e) => setMaxPrice(Number(e.target.value))}
            className="w-full"
          />
          <div className="flex items-center justify-between text-xs text-muted-foreground">
            <span>Up to £{(maxPrice ?? absoluteMaxPrice).toFixed(0)}</span>
            <button
              type="button"
              className="underline hover:no-underline"
              onClick={() => setMaxPrice(null)}
            >
              Clear
            </button>
          </div>
        </div>
      </FilterSection>

      {resultBrands.length > 0 && (
        <FilterSection title="Brand">
          {resultBrands.map((b) => (
            <label key={b} className="flex items-center gap-2 text-sm text-foreground mb-2 cursor-pointer">
              <input
                type="checkbox"
                checked={selectedBrands.includes(b)}
                onChange={() => toggleFilter(selectedBrands, b, setSelectedBrands)}
                className="rounded border-border text-primary focus:ring-primary"
              />
              {b}
            </label>
          ))}
        </FilterSection>
      )}
    </div>
  );

  return (
    <PageShell>
      <div
        className="relative h-40 md:h-52 bg-cover bg-center flex items-center justify-center"
        style={{ backgroundImage: `url(${heroBanner})` }}
      >
        <div className="absolute inset-0 bg-foreground/40" />
        <h1 className="relative text-2xl md:text-3xl font-display font-bold text-primary-foreground z-10 text-center px-4">
          {queryParam
            ? `Search: ${results.length} Results Found For "${queryParam}"`
            : "Search Products"}
        </h1>
      </div>

      <div className="container py-8">
        <div className="text-center mb-8">
          <h2 className="text-xl font-display font-bold text-foreground mb-4">Search results</h2>
          <form onSubmit={handleSearch} className="max-w-lg mx-auto relative">
            <input
              type="text"
              value={localQuery}
              onChange={(e) => setLocalQuery(e.target.value)}
              placeholder="Search products..."
              className="w-full h-12 pl-4 pr-20 rounded-lg border border-border bg-secondary text-foreground placeholder:text-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
            />
            {localQuery && (
              <button
                type="button"
                onClick={() => { setLocalQuery(""); setSearchParams({}); }}
                className="absolute right-12 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
              >
                <X className="h-4 w-4" />
              </button>
            )}
            <button
              type="submit"
              className="absolute right-1 top-1 h-10 w-10 bg-primary rounded-md flex items-center justify-center hover:opacity-90 transition-opacity"
            >
              <Search className="h-4 w-4 text-primary-foreground" />
            </button>
          </form>
        </div>

        {queryParam && (
          <>
            <button
              onClick={() => setShowMobileFilters(!showMobileFilters)}
              className="md:hidden flex items-center gap-2 mb-4 text-sm font-semibold text-primary border border-primary px-4 py-2 rounded-lg"
            >
              <SlidersHorizontal className="h-4 w-4" />
              {showMobileFilters ? "Hide Filters" : "Show Filters"}
            </button>

            <div className="flex gap-8">
              <aside className="hidden md:block w-60 shrink-0">{filterSidebar}</aside>
              {showMobileFilters && <aside className="md:hidden w-full mb-4">{filterSidebar}</aside>}

              <div className="flex-1">
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
                  <span className="text-sm text-muted-foreground">{results.length} results</span>
                </div>

                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                  {results.map((product) => (
                    <ProductCard key={product.id} {...product} />
                  ))}
                </div>

                {results.length === 0 && (
                  <div className="text-center py-16">
                    <p className="text-lg text-muted-foreground">No results found for &quot;{queryParam}&quot;.</p>
                    <p className="text-sm text-muted-foreground mt-2">Try different keywords or browse our collections.</p>
                  </div>
                )}
              </div>
            </div>
          </>
        )}
      </div>
    </PageShell>
  );
};

export default SearchResults;
