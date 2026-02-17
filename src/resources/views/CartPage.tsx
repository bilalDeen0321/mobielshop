import PageShell from "./PageShell";
import { useStore } from "@/context/StoreContext";
import { useNavigate } from "react-router-dom";

const CartPage = () => {
  const { cartItems, updateQuantity, removeFromCart, clearCart } = useStore();
  const navigate = useNavigate();

  const subtotal = cartItems.reduce(
    (sum, item) => sum + item.product.price * item.quantity,
    0,
  );

  return (
    <PageShell>
      <section className="container py-10">
        <h1 className="text-3xl font-display font-bold text-foreground mb-6">Your Cart</h1>
        {cartItems.length === 0 ? (
          <p className="text-sm text-muted-foreground">
            Your cart is currently empty. Browse our collections and add some great deals.
          </p>
        ) : (
          <div className="grid gap-8 lg:grid-cols-[2fr,1fr]">
            <div className="space-y-4">
              {cartItems.map((item) => (
                <div
                  key={item.product.id}
                  className="flex items-center gap-4 border border-border rounded-md p-3 bg-card"
                >
                  <img
                    src={item.product.image}
                    alt={item.product.name}
                    className="w-16 h-16 object-contain bg-secondary rounded-md"
                  />
                  <div className="flex-1">
                    <p className="text-sm font-semibold text-foreground">
                      {item.product.name}
                    </p>
                    <p className="text-xs text-muted-foreground">
                      {item.product.brand}
                    </p>
                    <p className="text-sm font-medium text-primary mt-1">
                      £{item.product.price.toFixed(2)}
                    </p>
                  </div>
                  <div className="flex items-center gap-2">
                    <button
                      type="button"
                      className="w-7 h-7 border border-border rounded-md text-sm"
                      onClick={() =>
                        updateQuantity(
                          item.product.id,
                          item.quantity - 1,
                        )
                      }
                    >
                      -
                    </button>
                    <span className="w-8 text-center text-sm">
                      {item.quantity}
                    </span>
                    <button
                      type="button"
                      className="w-7 h-7 border border-border rounded-md text-sm"
                      onClick={() =>
                        updateQuantity(
                          item.product.id,
                          item.quantity + 1,
                        )
                      }
                    >
                      +
                    </button>
                  </div>
                  <button
                    type="button"
                    className="text-xs text-muted-foreground hover:text-red-500 ml-2"
                    onClick={() => removeFromCart(item.product.id)}
                  >
                    Remove
                  </button>
                </div>
              ))}
            </div>
            <aside className="border border-border rounded-md p-4 bg-card h-fit space-y-3">
              <h2 className="text-base font-semibold text-foreground">Summary</h2>
              <div className="flex items-center justify-between text-sm">
                <span className="text-muted-foreground">Subtotal</span>
                <span className="font-semibold">
                  £{subtotal.toFixed(2)}
                </span>
              </div>
              <p className="text-xs text-muted-foreground">
                Shipping and discounts are calculated at checkout.
              </p>
              <button
                type="button"
                className="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-md bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                onClick={() => navigate("/checkout")}
              >
                Checkout
              </button>
              <button
                type="button"
                onClick={clearCart}
                className="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-md border border-border text-sm font-semibold text-muted-foreground hover:bg-secondary transition-colors"
              >
                Clear cart
              </button>
            </aside>
          </div>
        )}
      </section>
    </PageShell>
  );
};

export default CartPage;
