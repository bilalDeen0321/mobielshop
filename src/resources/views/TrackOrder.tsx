import PageShell from "./PageShell";

const TrackOrder = () => (
  <PageShell>
    <section className="container py-10 max-w-xl">
      <h1 className="text-3xl font-display font-bold text-foreground mb-4">Track Your Order</h1>
      <p className="text-sm text-muted-foreground mb-6">
        Enter your order number and email address to check the latest status and tracking details of
        your delivery.
      </p>
      <form className="space-y-4">
        <div>
          <label className="block text-xs font-medium text-muted-foreground mb-1" htmlFor="order">
            Order number
          </label>
          <input
            id="order"
            type="text"
            className="w-full h-10 px-3 rounded-md border border-border bg-card text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-muted-foreground mb-1" htmlFor="order-email">
            Email
          </label>
          <input
            id="order-email"
            type="email"
            className="w-full h-10 px-3 rounded-md border border-border bg-card text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
          />
        </div>
        <button
          type="submit"
          className="inline-flex items-center justify-center px-6 py-2.5 rounded-md bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
        >
          Track order
        </button>
      </form>
    </section>
  </PageShell>
);

export default TrackOrder;
