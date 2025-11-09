import { FileText } from 'lucide-react';
import ContentWrapper from '../components/ContentWrapper';
import { InfoBox } from '../components/InfoBox';
import { InfoBoxWrapper } from '../components/InfoBoxWrapper';

export default function PageDocuments() {
  return (
    <ContentWrapper icon={<FileText size={32} className="text-slate-800" />} title="Documents" >
      <p>
        This is the documents page.
      </p>
      <InfoBoxWrapper>
        <>
          {[1, 2, 3].map((num) => (
            <InfoBox key={num} title={`Document ${num}`}>
              <p>An piece of information.</p>
            </InfoBox>
          ))}
        </>
      </InfoBoxWrapper>
    </ContentWrapper>
  );
}
