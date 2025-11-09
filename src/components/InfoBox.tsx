export const InfoBox = ({ title, children }: { title: string; children: React.ReactNode }) => {
	return (
		<div className="p-6 bg-slate-50 rounded-xl border border-slate-200 hover:shadow-md transition-shadow">
			<h3 className="font-semibold text-slate-800 mb-2">{title}</h3>
			{children}
		</div>
	)
}