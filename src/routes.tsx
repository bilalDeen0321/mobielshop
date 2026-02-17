import { RouteObject } from "react-router-dom";
import Index from "@/resources/views/Index";
import Collections from "@/resources/views/Collections";
import SearchResults from "@/resources/views/SearchResults";
import ProductDetail from "@/resources/views/ProductDetail";
import About from "@/resources/views/About";
import Contact from "@/resources/views/Contact";
import Faqs from "@/resources/views/Faqs";
import Testimonials from "@/resources/views/Testimonials";
import TrackOrder from "@/resources/views/TrackOrder";
import CartPage from "@/resources/views/CartPage";
import WishlistPage from "@/resources/views/WishlistPage";
import Login from "@/resources/views/Login";
import Register from "@/resources/views/Register";
import Checkout from "@/resources/views/Checkout";
import Terms from "@/resources/views/Terms";
import NotFound from "@/resources/views/NotFound";

/**
 * Route configuration linking paths to views in resources/views
 */
export const routes: RouteObject[] = [
  { path: "/", element: <Index /> },
  { path: "/shop", element: <Collections /> },
  { path: "/collections/all", element: <Collections /> },
  { path: "/collections/:brand", element: <Collections /> },
  { path: "/search", element: <SearchResults /> },
  { path: "/product/:slug", element: <ProductDetail /> },
  { path: "/about", element: <About /> },
  { path: "/contact", element: <Contact /> },
  { path: "/faqs", element: <Faqs /> },
  { path: "/testimonials", element: <Testimonials /> },
  { path: "/track-order", element: <TrackOrder /> },
  { path: "/cart", element: <CartPage /> },
  { path: "/wishlist", element: <WishlistPage /> },
  { path: "/login", element: <Login /> },
  { path: "/register", element: <Register /> },
  { path: "/checkout", element: <Checkout /> },
  { path: "/terms", element: <Terms /> },
  { path: "*", element: <NotFound /> },
];
