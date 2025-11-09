import { useTypedStoreActions } from "./store/hooks";
import { useEffect } from "react";
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Layout from "./components/Layout";
import PageAbout from './pages/PageAbout';
import PageDocuments from './pages/PageDocuments';
import PageWelcome from './pages/PageWelcome';

function App() {
  const { initialize } = useTypedStoreActions((actions) => actions.mainModel);

  useEffect(() => {
    initialize();
  });

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<PageWelcome />} />
          <Route path="documents" element={<PageDocuments />} />
          <Route path="about" element={<PageAbout />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;