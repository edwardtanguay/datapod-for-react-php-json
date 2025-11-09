import { Box } from 'lucide-react';
import ContentWrapper from '../components/ContentWrapper';

import React, { useEffect, useState } from "react";
import axios from "axios";

interface Customer {
	suuid: string;
	firstName: string;
	lastName: string;
	address: string;
	zipcode: string;
	city: string;
}

const API_URL = "http://localhost:3312/customers";

export const PageCustomers = () => {

	const [customers, setCustomers] = useState<Customer[]>([]);
	const [formData, setFormData] = useState({
		firstName: "",
		lastName: "",
		address: "",
		zipcode: "",
		city: ""
	});
	const [editing, setEditing] = useState<Customer | null>(null);
	const [loading, setLoading] = useState(false);

	const fetchCustomers = async () => {
		setLoading(true);
		try {
			const res = await axios.get(API_URL);
			setCustomers(res.data);
		} catch (err) {
			console.error("Error fetching customers:", err);
		} finally {
			setLoading(false);
		}
	};

	useEffect(() => {
		fetchCustomers();
	}, []);

	const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		setFormData({ ...formData, [e.target.name]: e.target.value });
	};

	const handleSubmit = async (e: React.FormEvent) => {
		e.preventDefault();
		try {
			const data = { ...formData, suuid: editing?.suuid };
			if (editing) {
				await axios.put(`${API_URL}`, data);
			} else {
				await axios.post(API_URL, data);
			}
			setFormData({ firstName: "", lastName: "", address: "", zipcode: "", city: "" });
			setEditing(null);
			fetchCustomers();
		} catch (err) {
			console.error("Error saving customer:", err);
		}
	};

	const handleEdit = (customer: Customer) => {
		setEditing(customer);
		setFormData({
			firstName: customer.firstName,
			lastName: customer.lastName,
			address: customer.address,
			zipcode: customer.zipcode,
			city: customer.city,
		});
	};

	const handleDelete = async (id: string) => {
		if (!window.confirm("Delete this customer?")) return;
		try {
			await axios.delete(`${API_URL}/${id}`);
			fetchCustomers();
		} catch (err) {
			console.error("Error deleting customer:", err);
		}
	};

	return (
		<ContentWrapper icon={<Box size={32} className="text-slate-800" />} title="Customers" >
			<div className="min-h-screen bg-gray-50 p-6 flex flex-col items-center">
				<div className="w-full max-w-3xl bg-white rounded-xl shadow p-6">
					<h1 className="text-2xl font-semibold mb-4 text-center">
						Manage Customers
					</h1>

					<form
						onSubmit={handleSubmit}
						className="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6"
					>
						<input
							type="text"
							name="firstName"
							placeholder="First Name"
							value={formData.firstName}
							onChange={handleChange}
							required
							className="px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<input
							type="text"
							name="lastName"
							placeholder="Last Name"
							value={formData.lastName}
							onChange={handleChange}
							required
							className="px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<input
							type="text"
							name="address"
							placeholder="Address"
							value={formData.address}
							onChange={handleChange}
							required
							className="px-3 py-2 border rounded-md focus:ring focus:ring-blue-200 col-span-2"
						/>
						<input
							type="text"
							name="zipcode"
							placeholder="Zipcode"
							value={formData.zipcode}
							onChange={handleChange}
							required
							className="px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<input
							type="text"
							name="city"
							placeholder="City"
							value={formData.city}
							onChange={handleChange}
							required
							className="px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<button
							type="submit"
							className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition col-span-2"
						>
							{editing ? "Update" : "Add"}
						</button>
					</form>

					{loading ? (
						<p className="text-center text-gray-500">Loading...</p>
					) : (
						<div className="overflow-x-auto">
							<table className="w-full border-collapse">
								<thead>
									<tr className="bg-gray-100 text-left">
										<th className="p-3 border-b">SUUID</th>
										<th className="p-3 border-b">First Name</th>
										<th className="p-3 border-b">Last Name</th>
										<th className="p-3 border-b">Address</th>
										<th className="p-3 border-b">Zipcode</th>
										<th className="p-3 border-b">City</th>
										<th className="p-3 border-b text-right">Actions</th>
									</tr>
								</thead>
								<tbody>
									{customers.map((c) => (
										<tr key={c.suuid} className="hover:bg-gray-50">
											<td className="p-3 border-b opacity-30 font-mono">{c.suuid}</td>
											<td className="p-3 border-b">{c.firstName}</td>
											<td className="p-3 border-b">{c.lastName}</td>
											<td className="p-3 border-b">{c.address}</td>
											<td className="p-3 border-b">{c.zipcode}</td>
											<td className="p-3 border-b">{c.city}</td>
											<td className="p-3 border-b text-right space-x-2">
												<button
													onClick={() => handleEdit(c)}
													className="text-blue-600 hover:underline"
												>
													Edit
												</button>
												<button
													onClick={() => handleDelete(c.suuid)}
													className="text-red-600 hover:underline"
												>
													Delete
												</button>
											</td>
										</tr>
									))}
									{customers.length === 0 && (
										<tr>
											<td colSpan={7} className="text-center p-4 text-gray-500">
												No customers found.
											</td>
										</tr>
									)}
								</tbody>
							</table>
						</div>
					)}
				</div>
			</div>
		</ContentWrapper>
	);
};