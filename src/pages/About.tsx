import PageShell from "./PageShell";

const About = () => (
  <PageShell>
    <section className="container py-10">
      <h1 className="text-3xl font-display font-bold text-foreground mb-4">About LowPricePhones</h1>
      <p className="text-sm md:text-base text-muted-foreground max-w-3xl leading-relaxed">
        We specialise in affordable, factory-unlocked phones from leading brands including Apple,
        Samsung, Google, Huawei and more. Our mission is to make premium smartphones accessible to
        everyone, with transparent pricing, fast delivery and friendly customer support.
      </p>
    </section>
  </PageShell>
);

export default About;

