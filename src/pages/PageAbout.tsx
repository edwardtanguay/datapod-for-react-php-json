import { Info } from 'lucide-react';
import { InfoBox } from '../components/InfoBox';
import { InfoBoxWrapper } from '../components/InfoBoxWrapper';
import ContentWrapper from '../components/ContentWrapper';

export default function PageAbout() {
	return (
		<ContentWrapper icon={<Info size={32} className="text-slate-800" />} title="About" >
			<p>
				This is the about page.
			</p>
			<InfoBoxWrapper>
				<>
					{[1, 2, 3].map((num) => (
						<InfoBox key={num} title={`About Info ${num}`}>
							<p>An piece of information.</p>
						</InfoBox>
					))}
				</>
			</InfoBoxWrapper>
		</ContentWrapper>
	);
}
