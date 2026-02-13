import heroBanner from "@/assets/hero-banner.jpg";

const HeroBanner = () => (
  <section className="relative overflow-hidden">
    <div className="relative">
      <img
        src={heroBanner}
        alt="Latest smartphones and electronics deals"
        className="w-full h-[300px] sm:h-[400px] md:h-[480px] object-cover"
      />
      <div className="absolute inset-0 bg-gradient-to-r from-foreground/70 via-foreground/30 to-transparent" />
      <div className="absolute inset-0 flex items-center">
        <div className="container">
          <div className="max-w-lg text-primary-foreground animate-fade-in">
            <p className="text-sm sm:text-base font-medium mb-2 text-accent">New Arrivals 2025</p>
            <h2 className="text-3xl sm:text-4xl md:text-5xl font-display font-extrabold leading-tight mb-3">
              Latest Smartphones
              <br />
              <span className="text-primary">Best Prices</span>
            </h2>
            <p className="text-sm sm:text-base opacity-90 mb-6 max-w-sm">
              Discover amazing deals on the latest phones, tablets and accessories. Free shipping on orders over £250.
            </p>
            <a
              href="#products"
              className="inline-flex items-center gap-2 bg-primary text-primary-foreground px-7 py-3 rounded-lg font-semibold text-sm hover:opacity-90 transition-opacity"
            >
              Shop Now
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
);

export default HeroBanner;
