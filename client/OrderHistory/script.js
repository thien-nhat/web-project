function getCookieValue(name) {
	const regex = new RegExp(`(^| )${name}=([^;]+)`);
	const match = document.cookie.match(regex);
	if (match) {
		return match[2];
	}
}
// Fetch data from the API endpoint
fetch('http://localhost/WebProject/api/myorder', {
	headers: {
		Authorization: 'Bearer ' + getCookieValue('token'),
	},
})
	.then((response) => response.json())
	.then((data) => {
		// console.log(data);
		// Check if API request was successful
		if (data.status === 'success') {
			// Iterate over each order
			data.data.forEach((order) => {
				var faq = document.createElement('div');
				faq.classList.add('faq');

				// Create button element for order
				var button = document.createElement('button');
				button.classList.add('according');

				// Create span elements for order details
				var span = document.createElement('span');
				span.style.display = 'inline-block';
				span.style.textAlign = 'left';

				var orderIdSpan = document.createElement('span');
				orderIdSpan.classList.add('orderId');
				orderIdSpan.textContent = 'Order #' + order.orderId;

				var orderDateSpan = document.createElement('span');
				orderDateSpan.classList.add('orderDate');
				orderDateSpan.textContent = 'Delivery on ' + order.order_date;

				// Append order details to span element
				span.appendChild(orderIdSpan);
				span.appendChild(orderDateSpan);

				// Create link element for viewing status
				var link = document.createElement('a');
				link.href = '../OrderTracking/index.html?orderId=' + order.orderId; // Replace with actual URL
				link.classList.add('statusLink');
				link.textContent = 'View Status';

				// Append link to span element
				span.appendChild(link);

				// Append span element to button
				button.appendChild(span);

				// Create arrow icon element
				var arrow = document.createElement('span');
				arrow.classList.add('arrow');
				arrow.style.display = 'flex';
				arrow.style.width = '24px';
				arrow.style.height = '24px';
				arrow.style.justifyContent = 'center';
				arrow.style.alignItems = 'center';

				var icon = document.createElement('i');
				icon.classList.add('fa-solid', 'fa-caret-right');
				icon.style.fontSize = '18px';

				// Append icon to arrow
				arrow.appendChild(icon);

				// Append arrow to button
				button.appendChild(arrow);

				// Create panel for product containers
				var panel = document.createElement('div');
				panel.classList.add('pannel');
				panel.style.display = 'none'; // Hide panel initially

				// Append panel to button
				faq.appendChild(button);
				faq.appendChild(panel);

				// Append button to body
				document.querySelector('.col1').appendChild(faq);

				// Fetch product details for the current order
				fetch('http://localhost/WebProject/api/order/' + order.orderId)
					.then((response) => response.json())
					.then((productData) => {
						// Check if API request was successful
						if (productData.status === 'success') {
							// Iterate over each product in the order
							var productContainers = document.createElement('div');
							productContainers.classList.add('productContainers');
							productData.data.forEach((product) => {
								// Create product container
								var productContainer = document.createElement('div');
								productContainer.classList.add('productContainer');
								productContainers.appendChild(productContainer);

								// Create span elements for product details
								var productQuantity = document.createElement('div');
								productQuantity.classList.add('productQuantity');
								productQuantity.textContent =
									'x' + product.quantity + ' sản phẩm';

								var productName = document.createElement('div');
								productName.classList.add('productName');
								productName.textContent = product.productName;


								var productImage = document.createElement('img');
								productImage.classList.add('productImage');
								productImage.src = product.imageUrl;
								productImage.alt = 'Product Image';

								var productPrice = document.createElement('div');
								productPrice.classList.add('productPrice');
								productPrice.textContent = numberWithSeparator(product.price, ",") + " VND";
                                
								var productButton = document.createElement('div');
								productButton.classList.add('button');
								productButton.textContent = 'Mua thêm';

								var productButton1 = document.createElement('div');
								productButton1.classList.add('button1');
								productButton1.textContent = 'Chi tiết';

								var productLine = document.createElement('div');
								productLine.classList.add('line');

								// Append product details to product container
								productContainer.appendChild(productQuantity);
								productContainer.appendChild(productName);
								productContainer.appendChild(productImage);
								productContainer.appendChild(productPrice);
								productContainer.appendChild(productButton);
								productContainer.appendChild(productButton1);
								productContainer.appendChild(productLine);
							});
							panel.appendChild(productContainers);
						} else {
							// Handle error if API request failed
							console.error(
								'Failed to fetch product details:',
								productData.error
							);
						}
					})
					.catch((error) =>
						console.error('Error fetching product details:', error)
					);
			});
			// Make the button arrow work
			var faqs = document.getElementsByClassName('faq');
			Array.from(faqs).forEach(function (faq, index) {
				var button = faq.querySelector('.according');
				var arrow = faq.querySelector('.arrow');

				// Get the panel inside the current 'faq'
				var panel = faq.querySelector('.pannel');

				// Get the icon inside the current 'faq'
				var icon = button.querySelector('i');
				var orderIdSpan = button.querySelector('.orderId');
				var orderDateSpan = button.querySelector('.orderDate');
				// Add event listener to the button
				arrow.addEventListener('click', function () {
					// Toggle active class on button
					button.classList.toggle('active');
					// Toggle panel display
					panel.style.display =
						panel.style.display === 'block' ? 'none' : 'block';

					// Toggle caret icon
					icon.classList.toggle('fa-caret-right');
					icon.classList.toggle('fa-caret-down');

					// Swap orderId and orderDate for all faq elements

					// Swap their positions
					var temp = orderIdSpan.innerHTML;
					orderIdSpan.innerHTML = orderDateSpan.innerHTML;
					orderDateSpan.innerHTML = temp;
				});

				// If it's the first faq, initialize with panel and icon open
				if (index === 0) {
					button.classList.toggle('active');
					panel.style.display = 'block';
					icon.classList.remove('fa-caret-right');
					icon.classList.add('fa-caret-down');
					var temp = orderIdSpan.innerHTML;
					orderIdSpan.innerHTML = orderDateSpan.innerHTML;
					orderDateSpan.innerHTML = temp;
				}
			});
		} else {
			// Handle error if API request failed
			console.error('Failed to fetch orders:', data.error);
		}
	})
	.catch((error) => console.error('Error fetching orders:', error));
