import { Heart, ShoppingCart, User, Menu } from "lucide-react";
import { useNavigate } from "react-router-dom";
import { useStore } from "@/context/StoreContext";

interface HeaderActionsProps {
  onMobileNavToggle?: () => void;
}

const HeaderActions = ({ onMobileNavToggle }: HeaderActionsProps) => {
  const navigate = useNavigate();
  const { cartCount, wishlistCount } = useStore();

  return (
    <div className="flex items-center gap-1 sm:gap-3">
      <button
        type="button"
        onClick={() => navigate("/login")}
        className="hidden sm:flex flex-col items-center gap-0.5 text-muted-foreground hover:text-primary transition-colors p-2"
      >
        <User className="h-5 w-5" />
        <span className="text-[10px]">Account</span>
      </button>
      <button
        type="button"
        onClick={() => navigate("/wishlist")}
        className="hidden sm:flex flex-col items-center gap-0.5 text-muted-foreground hover:text-primary transition-colors p-2 relative"
      >
        <Heart className="h-5 w-5" />
        <span className="text-[10px]">Wishlist</span>
        {wishlistCount > 0 && (
          <span className="absolute top-0.5 right-0.5 h-4 min-w-4 px-0.5 bg-sale text-sale-foreground text-[10px] rounded-full flex items-center justify-center font-semibold">
            {wishlistCount}
          </span>
        )}
      </button>
      <button
        type="button"
        onClick={() => navigate("/cart")}
        className="flex flex-col items-center gap-0.5 text-muted-foreground hover:text-primary transition-colors p-2 relative"
      >
        <ShoppingCart className="h-5 w-5" />
        <span className="text-[10px]">Cart</span>
        {cartCount > 0 && (
          <span className="absolute top-0.5 right-0.5 h-4 min-w-4 px-0.5 bg-sale text-sale-foreground text-[10px] rounded-full flex items-center justify-center font-semibold">
            {cartCount}
          </span>
        )}
      </button>
      <button
        type="button"
        className="sm:hidden p-2 text-muted-foreground"
        onClick={() => onMobileNavToggle?.()}
      >
        <Menu className="h-5 w-5" />
      </button>
    </div>
  );
};

export default HeaderActions;
