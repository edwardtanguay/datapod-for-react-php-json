import { Home } from 'lucide-react';
import ContentWrapper from '../components/ContentWrapper';
import { FlashcardArea } from '../components/FlashcardArea';


export const PageWelcome = () => {
	return (
		<ContentWrapper icon={<Home size={32} className="text-slate-800" />} title="Welcome" >
			<FlashcardArea />
		</ContentWrapper>
	);
}
