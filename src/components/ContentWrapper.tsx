export default function ContentWrapper({ icon, title, children }: { icon: React.ReactNode; title: string, children: React.ReactNode }) {
	return (
		<div className="bg-white rounded-2xl shadow-lg p-6 sm:p-8 md:p-12">
			<div className="flex items-center gap-4 mb-6">
				{icon}
				<h2 className="text-3xl sm:text-4xl font-bold text-slate-800">{title}</h2>
			</div>

			<div className="prose max-w-none">
				{children}
			</div>
		</div>
	);
}
