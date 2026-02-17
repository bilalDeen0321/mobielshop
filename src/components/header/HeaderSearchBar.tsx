import { Search } from "lucide-react";
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { apiFetch } from "@/lib/api";

interface Suggestion {
  id: number;
  name: string;
  slug: string;
  brand?: string;
  price?: number;
}

const HeaderSearchBar = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const [suggestions, setSuggestions] = useState<Suggestion[]>([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [suggestLoading, setSuggestLoading] = useState(false);
  const navigate = useNavigate();

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
                <div className="font-medium text-foreground">{s.name}</div>
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
  );
};

export default HeaderSearchBar;
