const FooterNewsletter = () => (
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
);

export default FooterNewsletter;
