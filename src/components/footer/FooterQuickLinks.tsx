const quickLinks = [
  { label: "Home", href: "/" },
  { label: "Shop", href: "/shop" },
  { label: "About Us", href: "/about" },
  { label: "Contact", href: "/contact" },
  { label: "FAQs", href: "/faqs" },
];

const FooterQuickLinks = () => (
  <div>
    <h4 className="font-semibold mb-4 text-sm">Quick Links</h4>
    <ul className="space-y-2">
      {quickLinks.map((link) => (
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

export default FooterQuickLinks;
