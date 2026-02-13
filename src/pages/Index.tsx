import HeroBanner from "@/components/HeroBanner";
import BrandCategories from "@/components/BrandCategories";
import FeaturedProducts from "@/components/FeaturedProducts";
import PromoStrip from "@/components/PromoStrip";
import PageShell from "./PageShell";

const Index = () => (
  <PageShell>
    <HeroBanner />
    <BrandCategories />
    <FeaturedProducts />
    <PromoStrip />
  </PageShell>
);

export default Index;
