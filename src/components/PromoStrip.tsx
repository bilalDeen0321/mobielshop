import { Truck, ShieldCheck, RefreshCw, Headphones } from "lucide-react";

const features = [
  { icon: Truck, title: "Free Delivery", desc: "On orders over £250" },
  { icon: ShieldCheck, title: "Warranty", desc: "12 month guarantee" },
  { icon: RefreshCw, title: "Easy Returns", desc: "30 day return policy" },
  { icon: Headphones, title: "24/7 Support", desc: "Dedicated support team" },
];

const PromoStrip = () => (
  <section className="py-8 border-t border-border">
    <div className="container">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
        {features.map((f) => (
          <div key={f.title} className="flex items-center gap-3">
            <div className="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
              <f.icon className="h-5 w-5 text-primary" />
            </div>
            <div>
              <p className="text-sm font-semibold text-foreground">{f.title}</p>
              <p className="text-xs text-muted-foreground">{f.desc}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  </section>
);

export default PromoStrip;
