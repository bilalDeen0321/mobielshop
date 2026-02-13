import PageShell from "./PageShell";
import { useStore } from "@/context/StoreContext";

const WishlistPage = () => {
  const { wishlist, cartItems, addToCart, toggleWishlist } = useStore();

  // Derive simple products from cart + wishlist ids when adding; for now just show ids as list
  return (
    <PageShell>
      <section className="container py-10">
        <h1 className="text-3xl font-display font-bold text-foreground mb-6">Wishlist</h1>
        {wishlist.length === 0 ? (
          <p className="text-sm text-muted-foreground">
            Your wishlist is empty. Tap the heart icon on any product to save it for later.
          </p>
        ) : (
          <div className="space-y-3 text-sm text-muted-foreground">
            <p>You have {wishlist.length} items saved in your wishlist.</p>
            <p>
              (This project demo tracks wishlist items by product ID. You can extend it to show full
              product details or sync with a backend.)
            </p>
            <p>Wishlist product IDs: {wishlist.join(", ")}</p>
            <p>Items currently in cart: {cartItems.length}</p>
            <button
              type="button"
              onClick={() => wishlist.forEach((id) => addToCart({
                // placeholder minimal object, expects you to wire real products from your data source
                id,
                name: `Product #${id}`,
                brand: "Brand",
                category: "Mobile Phones",
                price: 0,
                image: "",
                rating: 4,
                condition: "New",
                inStock: true,
              }, 1))}
              className="mt-2 inline-flex items-center justify-center px-4 py-2.5 rounded-md bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
            >
              Add all wishlist items to cart (demo)
            </button>
            <button
              type="button"
              onClick={() => wishlist.forEach((id) => toggleWishlist(id))}
              className="mt-2 inline-flex items-center justify-center px-4 py-2.5 rounded-md border border-border text-sm font-semibold text-muted-foreground hover:bg-secondary transition-colors"
            >
              Clear wishlist
            </button>
          </div>
        )}
      </section>
    </PageShell>
  );
};

export default WishlistPage;

