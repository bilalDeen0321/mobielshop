import FooterBrand from "./footer/FooterBrand";
import FooterQuickLinks from "./footer/FooterQuickLinks";
import FooterCategories from "./footer/FooterCategories";
import FooterNewsletter from "./footer/FooterNewsletter";
import FooterBottom from "./footer/FooterBottom";

const StoreFooter = () => (
  <footer className="bg-nav text-nav-foreground">
    <div className="container py-12">
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <FooterBrand />
        <FooterQuickLinks />
        <FooterCategories />
        <FooterNewsletter />
      </div>
    </div>
    <FooterBottom />
  </footer>
);

export default StoreFooter;
