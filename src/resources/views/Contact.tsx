import PageShell from "./PageShell";

const Contact = () => (
  <PageShell>
    <section className="container py-10 grid gap-8 md:grid-cols-[1.2fr,1fr]">
      <div>
        <h1 className="text-3xl font-display font-bold text-foreground mb-4">Contact Us</h1>
        <p className="text-sm text-muted-foreground mb-6">
          Have a question about an order, warranty or product? Send us a message and we&apos;ll get
          back to you as soon as possible.
        </p>
        <form className="space-y-4 max-w-lg">
          <div>
            <label className="block text-xs font-medium text-muted-foreground mb-1" htmlFor="name">
              Name
            </label>
            <input
              id="name"
              type="text"
              className="w-full h-10 px-3 rounded-md border border-border bg-card text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
            />
          </div>
          <div>
            <label className="block text-xs font-medium text-muted-foreground mb-1" htmlFor="email">
              Email
            </label>
            <input
              id="email"
              type="email"
              className="w-full h-10 px-3 rounded-md border border-border bg-card text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
            />
          </div>
          <div>
            <label className="block text-xs font-medium text-muted-foreground mb-1" htmlFor="message">
              Message
            </label>
            <textarea
              id="message"
              rows={4}
              className="w-full px-3 py-2 rounded-md border border-border bg-card text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
            />
          </div>
          <button
            type="submit"
            className="inline-flex items-center justify-center px-6 py-2.5 rounded-md bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
          >
            Send message
          </button>
        </form>
      </div>
      <div className="space-y-4 text-sm text-muted-foreground">
        <h2 className="text-base font-semibold text-foreground">Store information</h2>
        <p>20 Bugsby&apos;s Way, SE7 7SJ, London, UK</p>
        <p>Phone: +44 7923 464508</p>
        <p>Email: support@lowpricephones.com</p>
      </div>
    </section>
  </PageShell>
);

export default Contact;
