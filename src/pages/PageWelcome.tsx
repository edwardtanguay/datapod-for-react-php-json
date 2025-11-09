import { Home } from 'lucide-react';
import ContentWrapper from '../components/ContentWrapper';
import { NavLink } from "react-router-dom";
import { InProgress } from '../components/InProgress';

export const PageWelcome = () => {
	return (
		<ContentWrapper icon={<Home size={32} className="text-slate-800" />} title="Welcome" >
			<h3>Get started</h3>
			<p>As an example of using PHP for the API, SQLite for the database, and React for the frontend to display data and perform CRUD operations, I've prepared three interconnected tables: Customers, Articles, Orders with corresponding CRUD pages as well was other common functionality such as reports, searches, exports, and imports.</p>
			<h3>CRUD Pages</h3>
			<p>There is a page for each of the database tables which allows you to add, edit and delete the items in the database:</p>
			<ul>
				<li>
					<NavLink to="/customers">Manage Customers</NavLink>
				</li>
				<li>
					<NavLink to="/articles">Manage Articles</NavLink>
				</li>
				<li>
					<NavLink to="/orders">Manage Orders</NavLink>
				</li>
			</ul>

			<h3>Report Page: Show Orders by Customer</h3>
			<ul>
				<li>Select customer</li>
				<li>Show all orders with total euro price of orders</li>
			</ul>
			<InProgress />

			<h3>Report Page: Filter Based on City</h3>
			<ul>
				<li>Filter buttons: [Berlin] [Non-Berlin]</li>
				<li>All customers with all info, and list of orders</li>
			</ul>
			<InProgress />

			<h3>Report Page: Customer Spending Total</h3>
			<ul>
				<li>All customers with sum of total orders</li>
				<li>Average customer order sum</li>
			</ul>
			<InProgress />

			<h3>Search Page</h3>
			<ul>
				<li>As you type</li>
				<li>Results show matching customers and articles</li>
			</ul>
			<InProgress />

			<h3>Export Page</h3>
			<ul>
				<li>Export customers as CSV file</li>
				<li>Export customers as JSON file</li>
				<li>Export customers as XML file</li>
				<li>exported files are stored in the /export directory</li>
			</ul>
			<InProgress />

			<h3>Import Page</h3>
			<ul>
				<li>place a customers file in the /import directory</li>
				<li>customers.csv</li>
				<li>customers.json</li>
				<li>customers.xml</li>
				<li>click [Import], customers are imported, file is copied to /archived directory</li>
			</ul>
			<InProgress />
		</ContentWrapper>
	);
}
