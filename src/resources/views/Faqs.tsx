import PageShell from "./PageShell";

const faqs = [
  {
    q: "What kinds of phones do you sell?",
    a: "We specialise in a variety of phones from major brands including Apple, Samsung, Google, Huawei and more. All phones are factory unlocked unless otherwise stated.",
  },
  {
    q: "Are your phones new, used or refurbished?",
    a: "We clearly state the condition on each product page. Stock ranges from brand new to refurbished and used excellent condition handsets.",
  },
  {
    q: "Do your phones come unlocked?",
    a: "Yes, all our phones are SIM-free and factory unlocked, so you can use any compatible UK or international network.",
  },
  {
    q: "What warranty do you provide?",
    a: "Most items come with 12 months warranty covering manufacturing faults and hardware issues. Please see individual products for exact details.",
  },
];

const Faqs = () => (
  <PageShell>
    <section className="container py-10 max-w-3xl">
      <h1 className="text-3xl font-display font-bold text-foreground mb-6">FAQ&apos;S</h1>
      <div className="space-y-4">
        {faqs.map((item) => (
          <div key={item.q} className="border border-border rounded-md p-4 bg-card">
            <h2 className="text-sm font-semibold text-foreground mb-1">{item.q}</h2>
            <p className="text-sm text-muted-foreground">{item.a}</p>
          </div>
        ))}
      </div>
    </section>
  </PageShell>
);

export default Faqs;
