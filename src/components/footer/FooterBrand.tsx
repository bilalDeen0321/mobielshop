import { ShoppingCart, Facebook, Twitter, Instagram, Youtube } from "lucide-react";

const socialIcons = [Facebook, Twitter, Instagram, Youtube];

const FooterBrand = () => (
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
      {socialIcons.map((Icon, i) => (
        <a
          key={i}
          href="#"
          className="w-8 h-8 rounded-full bg-nav-foreground/10 flex items-center justify-center hover:bg-primary transition-colors"
        >
          <Icon className="h-3.5 w-3.5" />
        </a>
      ))}
    </div>
  </div>
);

export default FooterBrand;
