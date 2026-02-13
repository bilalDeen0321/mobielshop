import PageShell from "./PageShell";

const Terms = () => (
  <PageShell>
    <section className="container py-10 max-w-3xl space-y-4 text-sm text-muted-foreground">
      <h1 className="text-3xl font-display font-bold text-foreground mb-2">
        Terms &amp; Conditions
      </h1>
      <p>
        These terms and conditions govern your use of the LowPricePhones
        storefront. By placing an order you agree to be bound by these terms as
        well as our Privacy and Shipping policies.
      </p>
      <h2 className="text-base font-semibold text-foreground mt-4">
        Orders &amp; pricing
      </h2>
      <p>
        All prices are shown in GBP (£) and include applicable taxes unless
        stated otherwise. We reserve the right to update prices and product
        availability at any time. An order is only accepted once you receive a
        shipping confirmation email.
      </p>
      <h2 className="text-base font-semibold text-foreground mt-4">
        Returns &amp; warranty
      </h2>
      <p>
        Eligible devices are supplied with at least 12 months warranty covering
        manufacturing and hardware faults. This does not cover accidental
        damage, misuse or unauthorised repairs. Please refer to your specific
        product page for full details.
      </p>
      <h2 className="text-base font-semibold text-foreground mt-4">
        Use of the website
      </h2>
      <p>
        You agree not to misuse this website, attempt unauthorised access or
        interfere with other users. Content and imagery are provided for your
        personal, non‑commercial use only.
      </p>
      <p className="text-[11px]">
        This demo project does not process real payments and is intended for
        development purposes only.
      </p>
    </section>
  </PageShell>
);

export default Terms;

