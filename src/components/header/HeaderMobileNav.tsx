import { NavLink } from "@/components/NavLink";

interface HeaderMobileNavProps {
  open: boolean;
  onClose: () => void;
}

const HeaderMobileNav = ({ open, onClose }: HeaderMobileNavProps) => {
  if (!open) return null;

  return (
    <div className="sm:hidden border-t border-border bg-nav text-nav-foreground">
      <div className="container py-3 space-y-1 text-sm">
        <NavLink
          to="/"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          Home
        </NavLink>
        <NavLink
          to="/shop"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          Shop
        </NavLink>
        <NavLink
          to="/collections/all"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          Collections
        </NavLink>
        <NavLink
          to="/shop?sale=true"
          onClick={onClose}
          className="block py-1.5 text-accent font-semibold"
        >
          Sale
        </NavLink>
        <NavLink
          to="/about"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          About Us
        </NavLink>
        <NavLink
          to="/contact"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          Contact
        </NavLink>
        <NavLink
          to="/testimonials"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          Testimonial
        </NavLink>
        <NavLink
          to="/faqs"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          FAQ&apos;S
        </NavLink>
        <NavLink
          to="/track-order"
          onClick={onClose}
          className="block py-1.5"
          activeClassName="text-primary"
        >
          Track Your Order
        </NavLink>
        <div className="pt-2 border-t border-border mt-2">
          <p className="text-[11px] font-semibold mb-1">Shop by categories</p>
          {["Mobile Phones", "Accessories", "Headphones", "Tablet"].map(
            (cat) => (
              <NavLink
                key={cat}
                to={`/collections/all?category=${encodeURIComponent(cat)}`}
                onClick={onClose}
                className="block py-1 text-xs"
              >
                {cat}
              </NavLink>
            ),
          )}
        </div>
      </div>
    </div>
  );
};

export default HeaderMobileNav;
