import { Box } from 'lucide-react';
import ContentWrapper from '../components/ContentWrapper';

import React, { useEffect, useState } from "react";
import axios from "axios";

interface Order {
	suuid: string;
	customer_suuid: string;
	article_suuid: string;
	amount: number;
}

const API_URL = "http://localhost:3312/orders";

export const PageOrders = () => {

	const [orders, setOrders] = useState<Order[]>([]);
	const [formData, setFormData] = useState({
		customer_suuid: "",
		article_suuid: "",
		amount: ""
	});
	const [editing, setEditing] = useState<Order | null>(null);
	const [loading, setLoading] = useState(false);

	const fetchOrders = async () => {
		setLoading(true);
		try {
			const res = await axios.get(API_URL);
			const _orders = res.data;
			_orders.forEach((order: Order) => {
				order.amount = parseInt(order.amount as unknown as string);
			});
			setOrders(_orders);
		} catch (err) {
			console.error("Error fetching orders:", err);
		} finally {
			setLoading(false);
		}
	};

	useEffect(() => {
		fetchOrders();
	}, []);

	const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		setFormData({ ...formData, [e.target.name]: e.target.value });
	};

	const handleSubmit = async (e: React.FormEvent) => {
		e.preventDefault();
		try {
			const data = {
				...formData,
				amount: parseInt(formData.amount),
				suuid: editing?.suuid
			};
			if (editing) {
				await axios.put(`${API_URL}`, data);
			} else {
				await axios.post(API_URL, data);
			}
			setFormData({ customer_suuid: "", article_suuid: "", amount: "" });
			setEditing(null);
			fetchOrders();
		} catch (err) {
			console.error("Error saving order:", err);
		}
	};

	const handleEdit = (order: Order) => {
		setEditing(order);
		setFormData({
			customer_suuid: order.customer_suuid,
			article_suuid: order.article_suuid,
			amount: order.amount.toString(),
		});
	};

	const handleDelete = async (id: string) => {
		if (!window.confirm("Delete this order?")) return;
		try {
			await axios.delete(`${API_URL}/${id}`);
			fetchOrders();
		} catch (err) {
			console.error("Error deleting order:", err);
		}
	};

	return (
		<ContentWrapper icon={<Box size={32} className="text-slate-800" />} title="Orders" >
			<div className="min-h-screen bg-gray-50 p-6 flex flex-col items-center">
				<div className="w-full max-w-2xl bg-white rounded-xl shadow p-6">
					<h1 className="text-2xl font-semibold mb-4 text-center">
						Manage Orders
					</h1>

					<form
						onSubmit={handleSubmit}
						className="flex flex-col sm:flex-row gap-3 mb-6"
					>
						<input
							type="text"
							name="customer_suuid"
							placeholder="Customer SUUID"
							value={formData.customer_suuid}
							onChange={handleChange}
							required
							className="flex-1 px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<input
							type="text"
							name="article_suuid"
							placeholder="Article SUUID"
							value={formData.article_suuid}
							onChange={handleChange}
							required
							className="flex-1 px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<input
							type="number"
							name="amount"
							placeholder="Amount"
							value={formData.amount}
							onChange={handleChange}
							required
							className="w-28 px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"
						/>
						<button
							type="submit"
							className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition"
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
										<th className="p-3 border-b">Customer SUUID</th>
										<th className="p-3 border-b">Article SUUID</th>
										<th className="p-3 border-b">Amount</th>
										<th className="p-3 border-b text-right">Actions</th>
									</tr>
								</thead>
								<tbody>
									{orders.map((o) => (
										<tr key={o.suuid} className="hover:bg-gray-50">
											<td className="p-3 border-b opacity-30 font-mono">{o.suuid}</td>
											<td className="p-3 border-b">{o.customer_suuid}</td>
											<td className="p-3 border-b">{o.article_suuid}</td>
											<td className="p-3 border-b">{o.amount}</td>
											<td className="p-3 border-b text-right space-x-2">
												<button
													onClick={() => handleEdit(o)}
													className="text-blue-600 hover:underline"
												>
													Edit
												</button>
												<button
													onClick={() => handleDelete(o.suuid)}
													className="text-red-600 hover:underline"
												>
													Delete
												</button>
											</td>
										</tr>
									))}
									{orders.length === 0 && (
										<tr>
											<td colSpan={5} className="text-center p-4 text-gray-500">
												No orders found.
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