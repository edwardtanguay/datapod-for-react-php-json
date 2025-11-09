import { useState } from "react";
import { NavLink, Outlet } from "react-router-dom";
import { Menu, X, Home, Info, Box } from "lucide-react";

export default function Layout() {
	const [isMenuOpen, setIsMenuOpen] = useState(false);

	const menuItems = [
		{ name: "Welcome", path: "/", icon: Home },
		{ name: "Customers", path: "/customers", icon: Box },
		{ name: "Articles", path: "/articles", icon: Box },
		{ name: "Orders", path: "/orders", icon: Box },
		{ name: "About", path: "/about", icon: Info },
	];

	return (
		<div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
			<header className="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
				<div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
					<div className="flex justify-between items-center h-16">
						<h1 className="text-xl sm:text-2xl font-bold text-slate-800 mr-3">
							Datapod PHP-React
						</h1>

						<nav className="hidden lg:flex space-x-1">
							{menuItems.map((item) => {
								const Icon = item.icon;
								return (
									<NavLink
										key={item.path}
										to={item.path}
										className={({ isActive }) =>
											`flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 ${isActive
												? "bg-slate-800 text-white shadow-md"
												: "text-slate-600 hover:bg-slate-100 hover:text-slate-900"
											}`
										}
									>
										<Icon size={18} />
										<span className="font-medium">
											{item.name}
										</span>
									</NavLink>
								);
							})}
						</nav>

						<button
							onClick={() => setIsMenuOpen(!isMenuOpen)}
							className="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors"
							aria-label="Toggle menu"
						>
							{isMenuOpen ? <X size={24} /> : <Menu size={24} />}
						</button>
					</div>
				</div>

				<div
					className={`lg:hidden overflow-hidden transition-all duration-300 ease-in-out ${isMenuOpen
						? "max-h-[600px] opacity-100"
						: "max-h-0 opacity-0"
						}`}
				>
					<nav className="px-4 pb-4 space-y-1 bg-white border-t border-slate-200">
						{menuItems.map((item) => {
							const Icon = item.icon;
							return (
								<NavLink
									key={item.path}
									to={item.path}
									onClick={() => setIsMenuOpen(false)}
									className={({ isActive }) =>
										`w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 ${isActive
											? "bg-slate-800 text-white shadow-md"
											: "text-slate-600 hover:bg-slate-100 hover:text-slate-900"
										}`
									}
								>
									<Icon size={20} />
									<span className="font-medium">
										{item.name}
									</span>
								</NavLink>
							);
						})}
					</nav>
				</div>
			</header>

			<main className="pt-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
				<div className="py-8 sm:py-12">
					<Outlet />
				</div>
			</main>
		</div>
	);
}
