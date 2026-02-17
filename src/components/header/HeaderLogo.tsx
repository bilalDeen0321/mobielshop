import { ShoppingCart } from "lucide-react";
import { useNavigate } from "react-router-dom";

const HeaderLogo = () => {
  const navigate = useNavigate();

  return (
    <button
      type="button"
      onClick={() => navigate("/")}
      className="flex items-center gap-2 shrink-0"
    >
      <div className="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
        <ShoppingCart className="h-5 w-5 text-primary-foreground" />
      </div>
      <div className="hidden sm:block text-left">
        <h1 className="text-xl font-display font-bold text-foreground leading-tight">
          Low<span className="text-primary">PricePhones</span>
        </h1>
        <p className="text-[10px] text-muted-foreground -mt-0.5">
          Best Deals on Unlocked Phones
        </p>
      </div>
    </button>
  );
};

export default HeaderLogo;
