import { Phone, Mail, Truck } from "lucide-react";

const TopBar = () => (
  <div className="bg-topbar text-topbar-foreground text-sm py-2">
    <div className="container flex items-center justify-between">
      <div className="flex items-center gap-4">
        <span className="flex items-center gap-1.5">
          <Truck className="h-3.5 w-3.5" />
          Free shipping on orders over £250
        </span>
      </div>
      <div className="hidden md:flex items-center gap-4">
        <a href="tel:+441234567890" className="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
          <Phone className="h-3.5 w-3.5" />
          +44 123 456 7890
        </a>
        <a href="mailto:support@techstore.com" className="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
          <Mail className="h-3.5 w-3.5" />
          support@techstore.com
        </a>
      </div>
    </div>
  </div>
);

export default TopBar;
