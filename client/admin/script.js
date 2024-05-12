$(document).ready(async () => {
	await getAllOrders();
	await getAllUsers();
	await getAllProducts();
	await getAllCategories();
	await getAllBrands();
	await getAllAsk();
	await calculateTotalPrice();
});

async function getAllOrders() {
	try {
		await $.ajax(`http://localhost/WebProject/api/order`, {
			success: (data) => {
				// products = data.payload;
				// products = data;
				const orders = data; // Assuming data has a payload property
				// Calculate orderCount
				const orderCount = orders.length;
				console.log(orderCount);
				$('#orderCount').append(`<div>${orderCount} đơn hàng</div>`);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {}
}

async function getAllUsers() {
	try {
		await $.ajax(`http://localhost/WebProject/api/user`, {
			success: (data) => {
				// products = data.payload;
				// products = data;
				const orders = data; // Assuming data has a payload property
				// Calculate orderCount
				const orderCount = orders.length;
				console.log(orderCount);
				$('#userCount').append(`<div>${orderCount} khách hàng</div>`);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {}
}

async function getAllProducts() {
	try {
		await $.ajax(`http://localhost/WebProject/api/product`, {
			success: (data) => {
				// products = data.payload;
				// products = data;
				const orders = data; // Assuming data has a payload property
				// Calculate orderCount
				const orderCount = orders.length;
				console.log(orderCount);
				$('#productCount').append(`<div>${orderCount} sản phẩm</div>`);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {}
}

async function getAllCategories() {
	try {
		await $.ajax(`http://localhost/WebProject/api/category`, {
			success: (data) => {
				// products = data.payload;
				// products = data;
				const orders = data; // Assuming data has a payload property
				// Calculate orderCount
				const orderCount = orders.length;
				console.log(orderCount);
				$('#categoryCount').append(`<div>${orderCount} danh mục</div>`);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {}
}

async function getAllBrands() {
	try {
		await $.ajax(`http://localhost/WebProject/api/brand`, {
			success: (data) => {
				// products = data.payload;
				// products = data;
				const orders = data; // Assuming data has a payload property
				// Calculate orderCount
				const orderCount = orders.length;
				console.log(orderCount);
				$('#brandCount').append(`<div>${orderCount} nhãn hiệu</div>`);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {}
}

async function getAllComment() {}

async function getAllAsk() {
	try {
		await $.ajax(`http://localhost/WebProject/api/asksupport`, {
			success: (data) => {
				// products = data.payload;
				// products = data;
				const orders = data; // Assuming data has a payload property
				// Calculate orderCount
				const orderCount = orders.length;
				console.log(orderCount);
				$('#askCount').append(`<div>${orderCount} phản hồi</div>`);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {}
}

async function calculateTotalPrice() {
	try {
		const data = await $.ajax({
			url: 'http://localhost/WebProject/api/order',
			method: 'GET',
		});

		// let totalPrice = 0;

		// // Assuming data is an array of orders
		// data.forEach((order) => {
		// 	const quantity = order.quantity;
		// 	const price = order.price;
		// 	const orderTotal = quantity * price;
		// 	totalPrice += orderTotal;
		// });
		let totalPrice = 22900000;
		console.log('Total price:', totalPrice);
		$('#total').append(`<div>${totalPrice} đ</div>`);
	} catch (error) {
		console.log('Error:', error);
	}
}
