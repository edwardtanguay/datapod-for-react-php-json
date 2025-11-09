import { createRoot } from "react-dom/client";
import { App } from "./App.tsx";
import { StoreProvider } from 'easy-peasy';
import "./index.scss";
import { store } from './store/store.ts';

createRoot(document.getElementById("root")!).render(
	<StoreProvider store={store}>
		<App />
	</StoreProvider>
);
