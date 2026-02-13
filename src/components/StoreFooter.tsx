import { ShoppingCart, Facebook, Twitter, Instagram, Youtube } from "lucide-react";

const StoreFooter = () => (
  <footer className="bg-nav text-nav-foreground">
    <div className="container py-12">
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        {/* Brand */}
        <div>
          <div className="flex items-center gap-2 mb-4">
            <div className="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
              <ShoppingCart className="h-4 w-4 text-primary-foreground" />
            </div>
            <span className="text-lg font-display font-bold">
              Tech<span className="text-primary">Store</span>
            </span>
          </div>
          <p className="text-sm opacity-70 mb-4 leading-relaxed">
            Your trusted source for the latest smartphones, tablets and accessories at the best prices.
          </p>
          <div className="flex gap-3">
            {[Facebook, Twitter, Instagram, Youtube].map((Icon, i) => (
              <a key={i} href="#" className="w-8 h-8 rounded-full bg-nav-foreground/10 flex items-center justify-center hover:bg-primary transition-colors">
                <Icon className="h-3.5 w-3.5" />
              </a>
            ))}
          </div>
        </div>

        {/* Quick Links */}
        <div>
          <h4 className="font-semibold mb-4 text-sm">Quick Links</h4>
          <ul className="space-y-2">
            {["Home", "Shop", "About Us", "Contact", "FAQs"].map((link) => (
              <li key={link}>
                <a
                  href={
                    link === "Home"
                      ? "/"
                      : link === "Shop"
                      ? "/shop"
                      : link === "About Us"
                      ? "/about"
                      : link === "Contact"
                      ? "/contact"
                      : "/faqs"
                  }
                  className="text-sm opacity-70 hover:opacity-100 hover:text-primary transition-all"
                >
                  {link}
                </a>
              </li>
            ))}
          </ul>
        </div>

        {/* Categories */}
        <div>
          <h4 className="font-semibold mb-4 text-sm">Categories</h4>
          <ul className="space-y-2">
            {["Mobile Phones", "Tablets", "Accessories", "Headphones", "Chargers"].map((link) => (
              <li key={link}>
                <a
                  href={
                    link === "Mobile Phones"
                      ? "/collections/all?category=Mobile%20Phones"
                      : link === "Tablets"
                      ? "/collections/all?category=Tablets"
                      : link === "Accessories"
                      ? "/collections/all?category=Accessories"
                      : link === "Headphones"
                      ? "/collections/all?category=Headphones"
                      : "/collections/all"
                  }
                  className="text-sm opacity-70 hover:opacity-100 hover:text-primary transition-all"
                >
                  {link}
                </a>
              </li>
            ))}
          </ul>
        </div>

        {/* Newsletter */}
        <div>
          <h4 className="font-semibold mb-4 text-sm">Newsletter</h4>
          <p className="text-sm opacity-70 mb-3">Subscribe for exclusive deals and updates.</p>
          <div className="flex">
            <input
              type="email"
              placeholder="Your email"
              className="flex-1 h-10 px-3 rounded-l-md bg-nav-foreground/10 border-none text-sm text-nav-foreground placeholder:text-nav-foreground/50 focus:outline-none focus:ring-1 focus:ring-primary"
            />
            <button className="h-10 px-4 bg-primary text-primary-foreground rounded-r-md text-sm font-semibold hover:opacity-90 transition-opacity">
              Subscribe
            </button>
          </div>
        </div>
      </div>
    </div>
    <div className="border-t border-nav-foreground/10 py-4">
      <div className="container flex flex-col sm:flex-row items-center justify-between gap-2 text-xs opacity-60">
        <p>© 2025 TechStore. All rights reserved.</p>
        <div className="flex gap-4">
          <a href="/terms" className="hover:text-primary transition-colors">Terms &amp; Conditions</a>
          <a href="/faqs" className="hover:text-primary transition-colors">FAQ&apos;S</a>
          <a href="/track-order" className="hover:text-primary transition-colors">Track Your Order</a>
        </div>
      </div>
    </div>
  </footer>
);

export default StoreFooter;
