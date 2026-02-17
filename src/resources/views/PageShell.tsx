import TopBar from "@/components/TopBar";
import HeaderBar from "@/components/HeaderBar";
import CategoryNav from "@/components/CategoryNav";
import StoreFooter from "@/components/StoreFooter";
import type { ReactNode } from "react";

const PageShell = ({ children }: { children: ReactNode }) => (
  <div className="min-h-screen bg-background flex flex-col">
    <TopBar />
    <HeaderBar />
    <CategoryNav />
    <main className="flex-1">{children}</main>
    <StoreFooter />
  </div>
);

export default PageShell;
