async function getProducts() {
	try {
		let res = await $.ajax({
			url: 'http://localhost/WebProject/api/product',
		});
		console.log(res);
		return res;
	} catch (error) {
		console.log(error);
	}
	return [];
}

async function getProductById(productId) {
	try {
		let res = await $.ajax({
			url: `http://localhost/WebProject/api/product/${productId}`,
		});
		return res[0];
	} catch (error) {
		console.log(error);
	}
	return [];
}

async function getCategories() {
	console.log('getCategories');
	let data;
	try {
		await $.ajax('http://localhost/WebProject/api/category', {
			success: (response) => {
				data = response;
				console.log(data);
			},
			error: (err) => {
				console.log(err);
			},
		});
	} catch (error) {
		console.error(error);
	}
	return data;
}

async function getReviews(userId = 0, productId = 0) {
	let params = [];
	if (userId) params.push(`userId=${userId}`);
	if (productId) params.push(`productId=${productId}`);
	try {
		let res = await $.ajax({
			url: `http://localhost:8080/backend/reviews${
				params.length ? '?' + params.join('&') : ''
			}`,
		});
		return res.payload;
	} catch (error) {
		console.log(error);
	}
	return [];
}

async function getCart() {
	let token = Cookies.get('token');

	if (!token) location.href = '../Signin/index.html';

	try {
		let res = await $.ajax({
			url: 'http://localhost/WebProject/api/mycart',
			beforeSend: (req) => {
				req.setRequestHeader('Authorization', `Bearer ${token}`);
			},
		});
		return res;
		// return res.payload;
	} catch (error) {
		console.log(error);
	}
}

async function addCart(productId, quantity) {
	let token = Cookies.get('token');

	if (!token) location.href = '../Signin/index.html';

	try {
		let res = await $.ajax('http://localhost/WebProject/api/cart', {
			method: 'POST',
			contentType: 'application/json',
			beforeSend: (req) => {
				req.setRequestHeader('Authorization', `Bearer ${token}`);
			},
			data: JSON.stringify({ productId, quantity }),
		});
		if (!(res.status == 'success')) {
			const errorJson = await res;
			if (errorJson.error) {
				throw new Error(`${errorJson.message}`);
			} else {
				console.log('Unknown error occurred.');
			}
		} else {
			alert("Thêm vào giỏ hàng thành công");
		}
	} catch (error) {
		console.log(error);
	}
}

async function addOrder(products) {
	let token = Cookies.get('token');
	if (!token) location.href = '../Signin/index.html';

	console.log(JSON.stringify({ products }));

	try {
		let temp = await $.ajax('http://localhost:8080/backend/userOrders', {
			method: 'POST',
			contentType: 'application/json',
			beforeSend: (req) => {
				req.setRequestHeader('Authorization', `Bearer ${token}`);
			},
			data: JSON.stringify({ products }),
		});
		console.log(temp);
	} catch (error) {
		console.log(error);
	}
}

function numberWithSeparator(num, separator) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${separator}`);
}
