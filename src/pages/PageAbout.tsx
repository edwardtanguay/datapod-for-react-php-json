import { Info } from 'lucide-react';
import ContentWrapper from '../components/ContentWrapper';

export const PageAbout = () => {
	return (
		<ContentWrapper icon={<Info size={32} className="text-slate-800" />} title="About this site" >
			<p className="mb-3">
				This site is a Datapod framework that uses PHP as a CLI language and API in the backend, and React/TypeScript/Tailwind as the frontend.
			</p>
			<p className="mb-3">
				To explore other Datapods that use other languages, see <a href="https://datapod-tanguay-eu.vercel.app" target="_blank" rel="noreferrer" className="text-blue-600 underline">The Datapod Project</a>.
			</p>
			<p>The Datapod Project is an open-source project created by Edward Tanguay.</p>
		</ContentWrapper>
	);
}
