import { Hammer } from 'lucide-react';

export const InProgress = () => {
	return (
		<div className='flex items-center gap-1 ml-6 italic text-red-700'>{<Hammer size={14} />}in progress</div>
	);
}