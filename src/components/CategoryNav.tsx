import { ChevronDown, LayoutGrid } from "lucide-react";
import { NavLink } from "./NavLink";
import { useState } from "react";

const brandItems = [
  { label: "Apple", path: "/collections/Apple" },
  { label: "Blackberry", path: "/collections/Blackberry" },
  { label: "Google", path: "/collections/Google" },
  { label: "Huawei", path: "/collections/Huawei" },
  { label: "Nokia", path: "/collections/Nokia" },
  { label: "Samsung", path: "/collections/Samsung" },
  { label: "Sony", path: "/collections/Sony" },
];

const CategoryNav = () => {
  const [showCategories, setShowCategories] = useState(false);
  const [showCollections, setShowCollections] = useState(false);

  return (
    <nav className="bg-nav text-nav-foreground">
      <div className="container flex items-center relative">
        {/* Categories Button */}
        <div
          className="relative"
          onMouseEnter={() => setShowCategories(true)}
          onMouseLeave={() => setShowCategories(false)}
        >
          <button
            className="flex items-center gap-2 bg-primary text-primary-foreground px-5 py-3.5 font-semibold text-sm hover:opacity-90 transition-opacity"
            type="button"
          >
            <LayoutGrid className="h-4 w-4" />
            SHOP BY CATEGORIES
            <ChevronDown className="h-4 w-4 ml-1" />
          </button>
          {showCategories && (
            <div className="absolute left-0 top-full mt-0.5 bg-card text-foreground border border-border rounded-md shadow-lg z-30 w-56">
              {["Mobile Phones", "Accessories", "Headphones", "Tablet"].map((cat) => (
                <NavLink
                  key={cat}
                  to={`/collections/all?category=${encodeURIComponent(cat)}`}
                  className="block px-4 py-2 text-sm hover:bg-secondary"
                >
                  {cat}
                </NavLink>
              ))}
            </div>
          )}
        </div>

        {/* Nav Links */}
        <div className="hidden md:flex items-center gap-1 ml-2">
          <NavLink
            to="/"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            Home
          </NavLink>
          <NavLink
            to="/shop"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            Shop
          </NavLink>

          {/* Collections with submenu */}
          <div
            className="relative"
            onMouseEnter={() => setShowCollections(true)}
            onMouseLeave={() => setShowCollections(false)}
          >
            <NavLink
              to="/collections/all"
              className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary flex items-center gap-1"
              activeClassName="text-primary"
            >
              Collections
              <ChevronDown className="h-3.5 w-3.5" />
            </NavLink>
            {showCollections && (
              <div className="absolute left-0 top-full mt-0.5 bg-card text-foreground border border-border rounded-md shadow-lg z-30 min-w-[200px]">
                {brandItems.map((item) => (
                  <NavLink
                    key={item.label}
                    to={item.path}
                    className="block px-4 py-2 text-sm hover:bg-secondary"
                  >
                    {item.label}
                  </NavLink>
                ))}
              </div>
            )}
          </div>

          <NavLink
            to="/shop?sale=true"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary text-accent font-bold"
          >
            Sale
          </NavLink>
          <NavLink
            to="/about"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            About Us
          </NavLink>
          <NavLink
            to="/contact"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            Contact
          </NavLink>
          <NavLink
            to="/testimonials"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            Testimonial
          </NavLink>
          <NavLink
            to="/faqs"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            FAQ&apos;S
          </NavLink>
          <NavLink
            to="/track-order"
            className="px-4 py-3.5 text-sm font-medium transition-colors hover:text-primary"
            activeClassName="text-primary"
          >
            Track Your Order
          </NavLink>
        </div>
      </div>
    </nav>
  );
};

export default CategoryNav;

