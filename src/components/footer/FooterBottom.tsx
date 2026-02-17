const FooterBottom = () => (
  <div className="border-t border-nav-foreground/10 py-4">
    <div className="container flex flex-col sm:flex-row items-center justify-between gap-2 text-xs opacity-60">
      <p>© 2025 TechStore. All rights reserved.</p>
      <div className="flex gap-4">
        <a href="/terms" className="hover:text-primary transition-colors">
          Terms &amp; Conditions
        </a>
        <a href="/faqs" className="hover:text-primary transition-colors">
          FAQ&apos;S
        </a>
        <a href="/track-order" className="hover:text-primary transition-colors">
          Track Your Order
        </a>
      </div>
    </div>
  </div>
);

export default FooterBottom;
