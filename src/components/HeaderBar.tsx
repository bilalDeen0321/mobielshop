import { useState } from "react";
import HeaderLogo from "./header/HeaderLogo";
import HeaderSearchBar from "./header/HeaderSearchBar";
import HeaderActions from "./header/HeaderActions";
import HeaderMobileNav from "./header/HeaderMobileNav";

const HeaderBar = () => {
  const [showMobileNav, setShowMobileNav] = useState(false);

  return (
    <header className="bg-card border-b border-border py-4">
      <div className="container flex items-center justify-between gap-4">
        <HeaderLogo />
        <HeaderSearchBar />
        <HeaderActions onMobileNavToggle={() => setShowMobileNav((prev) => !prev)} />
      </div>
      <HeaderMobileNav open={showMobileNav} onClose={() => setShowMobileNav(false)} />
    </header>
  );
};

export default HeaderBar;
