import { Search, Heart, ShoppingCart, User, Menu } from "lucide-react";
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useStore } from "@/context/StoreContext";
import { apiFetch } from "@/lib/api";
import { NavLink } from "./NavLink";

interface Suggestion {
  id: number;
  name: string;
  slug: string;
  brand?: string;
  price?: number;
}

const HeaderBar = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const [suggestions, setSuggestions] = useState<Suggestion[]>([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [suggestLoading, setSuggestLoading] = useState(false);
   const [showMobileNav, setShowMobileNav] = useState(false);
  const navigate = useNavigate();
  const { cartCount, wishlistCount } = useStore();

  useEffect(() => {
    if (!searchQuery || searchQuery.trim().length < 2) {
      setSuggestions([]);
      setShowSuggestions(false);
      return;
    }
    const handle = setTimeout(async () => {
      try {
        setSuggestLoading(true);
        const res = await apiFetch(
          `/api/products/suggest?q=${encodeURIComponent(searchQuery.trim())}`,
        );
        const data = await res.json();
        if (!res.ok) {
          console.error(data);
          return;
        }
        setSuggestions(data.data ?? []);
        setShowSuggestions(true);
      } catch (err) {
        console.error("Suggest error", err);
      } finally {
        setSuggestLoading(false);
      }
    }, 250);
    return () => clearTimeout(handle);
  }, [searchQuery]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
      setShowSuggestions(false);
    }
  };

  const handleSelectSuggestion = (slug: string) => {
    setShowSuggestions(false);
    setSearchQuery("");
    setSuggestions([]);
    navigate(`/product/${slug}`);
  };

  return (
    <header className="bg-card border-b border-border py-4">
      <div className="container flex items-center justify-between gap-4">
        {/* Logo */}
        <button
          type="button"
          onClick={() => navigate("/")}
          className="flex items-center gap-2 shrink-0"
        >
          <div className="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
            <ShoppingCart className="h-5 w-5 text-primary-foreground" />
          </div>
          <div className="hidden sm:block text-left">
            <h1 className="text-xl font-display font-bold text-foreground leading-tight">
              Low<span className="text-primary">PricePhones</span>
            </h1>
            <p className="text-[10px] text-muted-foreground -mt-0.5">
              Best Deals on Unlocked Phones
            </p>
          </div>
        </button>

        {/* Search Bar */}
        <form onSubmit={handleSearch} className="flex-1 max-w-xl">
          <div className="relative">
            <input
              type="text"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              placeholder="Search for phones, tablets, accessories..."
              className="w-full h-11 pl-4 pr-12 rounded-lg border border-border bg-secondary text-foreground placeholder:text-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
            />
            <button
              type="submit"
              className="absolute right-1 top-1 h-9 w-10 bg-primary rounded-md flex items-center justify-center hover:opacity-90 transition-opacity"
            >
              <Search className="h-4 w-4 text-primary-foreground" />
            </button>
            {showSuggestions && (suggestions.length > 0 || suggestLoading) && (
              <div className="absolute left-0 right-0 mt-1 bg-card border border-border rounded-md shadow-lg z-40 max-h-64 overflow-auto">
                {suggestions.map((s) => (
                  <button
                    key={s.id}
                    type="button"
                    onClick={() => handleSelectSuggestion(s.slug)}
                    className="w-full text-left px-3 py-2 hover:bg-secondary text-sm"
                  >
                    <div className="font-medium text-foreground">
                      {s.name}
                    </div>
                    <div className="text-[11px] text-muted-foreground">
                      {s.brand && <span>{s.brand}</span>}
                      {s.price != null && (
                        <span> • £{Number(s.price).toFixed(2)}</span>
                      )}
                    </div>
                  </button>
                ))}
                {suggestLoading && (
                  <div className="px-3 py-2 text-xs text-muted-foreground">
                    Searching...
                  </div>
                )}
              </div>
            )}
          </div>
        </form>

        {/* Actions */}
        <div className="flex items-center gap-1 sm:gap-3">
          <button
            type="button"
            onClick={() => navigate("/login")}
            className="hidden sm:flex flex-col items-center gap-0.5 text-muted-foreground hover:text-primary transition-colors p-2"
          >
            <User className="h-5 w-5" />
            <span className="text-[10px]">Account</span>
          </button>
          <button
            type="button"
            onClick={() => navigate("/wishlist")}
            className="hidden sm:flex flex-col items-center gap-0.5 text-muted-foreground hover:text-primary transition-colors p-2 relative"
          >
            <Heart className="h-5 w-5" />
            <span className="text-[10px]">Wishlist</span>
            {wishlistCount > 0 && (
              <span className="absolute top-0.5 right-0.5 h-4 min-w-4 px-0.5 bg-sale text-sale-foreground text-[10px] rounded-full flex items-center justify-center font-semibold">
                {wishlistCount}
              </span>
            )}
          </button>
          <button
            type="button"
            onClick={() => navigate("/cart")}
            className="flex flex-col items-center gap-0.5 text-muted-foreground hover:text-primary transition-colors p-2 relative"
          >
            <ShoppingCart className="h-5 w-5" />
            <span className="text-[10px]">Cart</span>
            {cartCount > 0 && (
              <span className="absolute top-0.5 right-0.5 h-4 min-w-4 px-0.5 bg-sale text-sale-foreground text-[10px] rounded-full flex items-center justify-center font-semibold">
                {cartCount}
              </span>
            )}
          </button>
          <button
            type="button"
            className="sm:hidden p-2 text-muted-foreground"
            onClick={() => setShowMobileNav((prev) => !prev)}
          >
            <Menu className="h-5 w-5" />
          </button>
        </div>
      </div>
      {showMobileNav && (
        <div className="sm:hidden border-t border-border bg-nav text-nav-foreground">
          <div className="container py-3 space-y-1 text-sm">
            <NavLink
              to="/"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              Home
            </NavLink>
            <NavLink
              to="/shop"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              Shop
            </NavLink>
            <NavLink
              to="/collections/all"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              Collections
            </NavLink>
            <NavLink
              to="/shop?sale=true"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5 text-accent font-semibold"
            >
              Sale
            </NavLink>
            <NavLink
              to="/about"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              About Us
            </NavLink>
            <NavLink
              to="/contact"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              Contact
            </NavLink>
            <NavLink
              to="/testimonials"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              Testimonial
            </NavLink>
            <NavLink
              to="/faqs"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              FAQ&apos;S
            </NavLink>
            <NavLink
              to="/track-order"
              onClick={() => setShowMobileNav(false)}
              className="block py-1.5"
              activeClassName="text-primary"
            >
              Track Your Order
            </NavLink>
            <div className="pt-2 border-t border-border mt-2">
              <p className="text-[11px] font-semibold mb-1">
                Shop by categories
              </p>
              {["Mobile Phones", "Accessories", "Headphones", "Tablet"].map(
                (cat) => (
                  <NavLink
                    key={cat}
                    to={`/collections/all?category=${encodeURIComponent(cat)}`}
                    onClick={() => setShowMobileNav(false)}
                    className="block py-1 text-xs"
                  >
                    {cat}
                  </NavLink>
                ),
              )}
            </div>
          </div>
        </div>
      )}
    </header>
  );
};

export default HeaderBar;

