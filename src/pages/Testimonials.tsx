import PageShell from "./PageShell";

const testimonials = [
  {
    quote:
      "Fast delivery, exactly as described i.e. sealed and box unopened, well pleased, 5* seller thanks.",
    name: "n***n",
    product: "Samsung Galaxy A20e",
  },
  {
    quote:
      "I would like to thank you very much for the phone, my daughter is very satisfied. Highly recommended.",
    name: "O***O",
    product: "Huawei P30 Lite",
  },
  {
    quote:
      "A very good item at an excellent price. Communication with the vendor was also top notch.",
    name: "9***l",
    product: "Example product",
  },
];

const Testimonials = () => (
  <PageShell>
    <section className="container py-10 max-w-4xl">
      <h1 className="text-3xl font-display font-bold text-foreground mb-6">Testimonials</h1>
      <div className="grid gap-6 md:grid-cols-3">
        {testimonials.map((t) => (
          <div key={t.name} className="bg-card border border-border rounded-md p-4 text-sm">
            <p className="text-muted-foreground mb-3">“{t.quote}”</p>
            <p className="font-semibold text-foreground text-xs">{t.name}</p>
            <p className="text-[11px] text-muted-foreground">{t.product}</p>
          </div>
        ))}
      </div>
    </section>
  </PageShell>
);

export default Testimonials;

