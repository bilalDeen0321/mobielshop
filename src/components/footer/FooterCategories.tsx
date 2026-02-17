const categoryLinks = [
  { label: "Mobile Phones", href: "/collections/all?category=Mobile%20Phones" },
  { label: "Tablets", href: "/collections/all?category=Tablets" },
  { label: "Accessories", href: "/collections/all?category=Accessories" },
  { label: "Headphones", href: "/collections/all?category=Headphones" },
  { label: "Chargers", href: "/collections/all" },
];

const FooterCategories = () => (
  <div>
    <h4 className="font-semibold mb-4 text-sm">Categories</h4>
    <ul className="space-y-2">
      {categoryLinks.map((link) => (
        <li key={link.label}>
          <a
            href={link.href}
            className="text-sm opacity-70 hover:opacity-100 hover:text-primary transition-all"
          >
            {link.label}
          </a>
        </li>
      ))}
    </ul>
  </div>
);

export default FooterCategories;
