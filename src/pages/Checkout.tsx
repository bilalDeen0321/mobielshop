import PageShell from "./PageShell";
import { useStore } from "@/context/StoreContext";

const Checkout = () => {
  const { cartItems } = useStore();

  const total = cartItems.reduce(
    (sum, item) => sum + item.product.price * item.quantity,
    0,
  );

  return (
    <PageShell>
      <section className="container py-10 grid gap-8 lg:grid-cols-[2fr,1fr]">
        <div className="space-y-6">
          <h1 className="text-3xl font-display font-bold text-foreground">
            Checkout
          </h1>

          <div className="grid gap-4 md:grid-cols-2">
            <div className="space-y-3 border border-border rounded-md p-4 bg-card">
              <h2 className="text-sm font-semibold text-foreground">
                Shipping details
              </h2>
              <div className="space-y-2">
                <div>
                  <label className="block text-xs text-muted-foreground mb-1">
                    Full name
                  </label>
                  <input
                    type="text"
                    className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                  />
                </div>
                <div>
                  <label className="block text-xs text-muted-foreground mb-1">
                    Address
                  </label>
                  <input
                    type="text"
                    className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-xs text-muted-foreground mb-1">
                      City
                    </label>
                    <input
                      type="text"
                      className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                    />
                  </div>
                  <div>
                    <label className="block text-xs text-muted-foreground mb-1">
                      Postcode
                    </label>
                    <input
                      type="text"
                      className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                    />
                  </div>
                </div>
                <div>
                  <label className="block text-xs text-muted-foreground mb-1">
                    Country
                  </label>
                  <input
                    type="text"
                    className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                    defaultValue="United Kingdom"
                  />
                </div>
              </div>
            </div>

            <div className="space-y-3 border border-border rounded-md p-4 bg-card">
              <h2 className="text-sm font-semibold text-foreground">
                Card details
              </h2>
              <div className="space-y-2">
                <div>
                  <label className="block text-xs text-muted-foreground mb-1">
                    Name on card
                  </label>
                  <input
                    type="text"
                    className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                  />
                </div>
                <div>
                  <label className="block text-xs text-muted-foreground mb-1">
                    Card number
                  </label>
                  <input
                    type="text"
                    inputMode="numeric"
                    className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="XXXX XXXX XXXX XXXX"
                  />
                </div>
                <div className="grid grid-cols-[1fr,1fr,0.8fr] gap-2">
                  <div>
                    <label className="block text-xs text-muted-foreground mb-1">
                      Expiry month
                    </label>
                    <input
                      type="text"
                      inputMode="numeric"
                      className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                      placeholder="MM"
                    />
                  </div>
                  <div>
                    <label className="block text-xs text-muted-foreground mb-1">
                      Expiry year
                    </label>
                    <input
                      type="text"
                      inputMode="numeric"
                      className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                      placeholder="YY"
                    />
                  </div>
                  <div>
                    <label className="block text-xs text-muted-foreground mb-1">
                      CVV
                    </label>
                    <input
                      type="password"
                      inputMode="numeric"
                      className="w-full h-9 px-2 rounded-md border border-border bg-background text-xs focus:outline-none focus:ring-2 focus:ring-primary/40"
                      placeholder="***"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <p className="text-[11px] text-muted-foreground">
            By placing your order you agree to our{" "}
            <a
              href="/terms"
              className="text-primary underline hover:no-underline"
            >
              Terms &amp; Conditions
            </a>
            .
          </p>
        </div>

        <aside className="border border-border rounded-md p-4 bg-card h-fit space-y-3">
          <h2 className="text-base font-semibold text-foreground">
            Order summary
          </h2>
          <div className="space-y-1 text-xs text-muted-foreground max-h-40 overflow-auto pr-1">
            {cartItems.map((item) => (
              <div
                key={item.product.id}
                className="flex justify-between gap-2"
              >
                <span className="truncate">
                  {item.quantity} × {item.product.name}
                </span>
                <span className="whitespace-nowrap">
                  £{(item.product.price * item.quantity).toFixed(2)}
                </span>
              </div>
            ))}
          </div>
          <div className="flex items-center justify-between text-sm pt-2 border-t border-border">
            <span className="text-muted-foreground">Total</span>
            <span className="font-semibold">£{total.toFixed(2)}</span>
          </div>
          <button
            type="button"
            className="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-md bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
          >
            Pay now
          </button>
        </aside>
      </section>
    </PageShell>
  );
};

export default Checkout;

