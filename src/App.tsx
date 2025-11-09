import { useTypedStoreActions } from "./store/hooks";
import { useEffect } from "react";
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Layout from "./components/Layout";
import { PageAbout } from './pages/PageAbout';
import { PageWelcome } from './pages/PageWelcome';
import { PageArticles } from "./pages/PageArticles";
import { PageOrders } from "./pages/PageOrders";
import { PageCustomers } from "./pages/PageCustomers";

export const App = () => {
  const { initialize } = useTypedStoreActions((actions) => actions.mainModel);

  useEffect(() => {
    initialize();
  });

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<PageWelcome />} />
          <Route path="customers" element={<PageCustomers />} />
          <Route path="articles" element={<PageArticles />} />
          <Route path="orders" element={<PageOrders />} />
          <Route path="about" element={<PageAbout />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;