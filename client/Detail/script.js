const productId = new URLSearchParams(location.search).get('id');
// let reviews = [];

function imageSlider() {
	// ! CLICK IMG IN IMG-MAIN
	const imgMain = document.querySelector('#itemMain');
	// console.log(imgMain.getAttribute("src"))
	const sliderImg = document.querySelectorAll('.itemImg');
	sliderImg.forEach((item) => {
		item.addEventListener('click', function (e) {
			// console.log(e.target)
			let srcItem = e.target.getAttribute('src');
			// console.log(srcItem);
			if (imgMain.getAttribute('src') != srcItem) {
				imgMain.setAttribute('src', srcItem);
			}
		});
	});
}

const radio = document.querySelectorAll('.pickColor');
// console.log(radio);
radio.forEach((item) => {
	item.addEventListener('change', function (e) {
		//   console.log(item.style.backgroundColor)
		//   console.log(e.target.value);
		let setColor = e.target.value;
		if (e.target.checked) {
			e.target.setAttribute('accent-color', 'yellow');
		} else {
			e.target.setAttribute('accent-color', 'unset');
		}
	});
});

// ! TAB SITE IN DESC AND REVIEW
document.addEventListener('DOMContentLoaded', function () {
	const listMenu = document.querySelectorAll('.btn-dir');
	const listContent = document.querySelectorAll('.itemDesc');

	listMenu.forEach((item) => {
		item.addEventListener('click', function () {
			resetBTN();
			// co<nav></nav>
			console.log(item.value);
			item.classList.toggle('checked');
			showContent(Number(item.value));
		});
	});
	function resetBTN() {
		listMenu.forEach((item) => {
			item.classList.remove('checked');
			item.classList.remove('default');
		});
		listContent.forEach((item) => {
			item.style.display = 'none';
		});
	}
	function showContent(value) {
		if (value === 0) {
			listContent[value].style.display = 'grid';
		} else if (value === 1) {
			listContent[value].style.display = 'block';
		} else if (value === 2) {
			listContent[value].style.display = 'flex';
		}
	}
});

// ! INPUT START TO FORM COMMENT:
document.addEventListener('DOMContentLoaded', function () {
	var stars = document.querySelectorAll('.star');
	var ratingContainer = document.getElementById('ratingContainer');
	var ratingStars = document.getElementById('ratingStars');
	var selectedRating = document.getElementById('selectedRating');

	stars.forEach(function (star) {
		star.addEventListener('click', function () {
			resetStar();
			var ratingValue = star.dataset.value;
			selectedRating.textContent = 'Selected rating: ' + ratingValue;
			selectedRating.dataset.result = ratingValue;
			highlightStars(ratingValue);
		});
	});
	function resetStar() {
		stars.forEach((item) => {
			item.classList.remove('selected');
		});
	}
	function highlightStars(value) {
		stars.forEach(function (star) {
			if (star.dataset.value <= value) {
				star.classList.add('selected');
			}
		});
	}

	function clearHighlights() {
		stars.forEach(function (star) {
			star.classList.remove('selected');
		});
	}
});
let product = {};

// $(document).ready(async function () {
// 	try {
// 		product = await $.ajax({
// 			url: `http://localhost/WebProject/api/product/${productId}`,
// 		});
// 		product = product.payload[0];
// 	} catch (error) {}
// 	console.log("Product data.....", product);

// 	reviews = await getReviews(0, productId);

// 	console.log(reviews);

// 	showProduct();
// 	showReviews();
// 	imageSlider();
// });

$(document).ready(async function () {
	try {
		product = await $.ajax({
			url: `http://localhost/WebProject/api/product/${productId}`,
		});
		product = product[0]; // Access the first element of the returned data
	} catch (error) {}
	console.log('Product data.....', product);

	// Show reviews
	try {
		reviews = await $.ajax({
			url: `http://localhost/WebProject/api/review/${productId}`,
		});
		showReviews(reviews.data);
	} catch (error) {
		console.log('Review error', error);
	}
	// console.log("Review data....", reviews.data);
	showProduct(reviews);
	imageSlider();

	// showReviews(reviews.data);
});

function showProduct(reviews) {
	$('.nameProduct').text(product.name);
	// $("#inStock").text(product.quantity ? "Còn hàng" : "Hết hàng");
	// $("#categoryProd").text(product.categoryId + " temp");

	$('.desc1').text(product.description);

	$('#initPrice').text(numberWithSeparator(product.price, ',') + ' VND');
	$('#finalPrice').text(
		numberWithSeparator(product.price * (1 - product.discount), ',') + ' VND'
	);
	$('#valDisc').text(product.discount * 100 + '% OFF');
	if (!parseFloat(product.discount)) {
		$('#initPrice').css('display', 'none');
		$('#valDisc').css('display', 'none');
	}
	$('.listImg').html('');

	$('.imgMain img').attr('src', product.images[0]);

	product.images.forEach((img) => {
		// console.log(img);
		$('.listImg').append(
			$(`
				<button class="itemImg btn-img">
					<img style="width: 100%;height: 100%;object-fit: cover;" class="imgSlider" src="${img}" alt="" />
				</button>
			`)
		);
	});

	// $(".listColor").html("");
	// const colors = [
	// 	...new Set(
	// 		product.colorSizes.map((obj) => `${obj.color}$${obj.colorName}`)
	// 	),
	// ];
	// colors.forEach((color, index) => {
	// 	color = color.split("$");
	// 	$(".listColor").append(
	// 		$(`
	// 			<div class="colorSelect">
	// 				<input type="radio" value="${
	// 					color[0]
	// 				}" name="color" class="pickColor" id="pick${color[1]}" ${
	// 			index ? "" : "checked"
	// 		}>
	// 				<label for="pick${color[1]}">${color[1]}</label>
	// 			</div>
	// 		`)
	// 	);
	// });

	// $("#pickSize").html("");
	// const sizes = [...new Set(product.colorSizes.map((obj) => obj.size))];
	// sizes.forEach((size) => {
	// 	$("#pickSize").append($(`<option value="${size}">${size}</option>`));
	// });

	// Review
	let sum = 0;
	for (let i = 0; i < reviews.data.length; i++) {
		sum += Number(reviews.data[i].rating);
	}
	let average = sum / reviews.data.length;
	$('#rating').text(average);
	// console.log('Review result ......', reviews.result);
	$('#user-cmt').text(`(${reviews.result} đánh giá từ người dùng)`);
}

function generateStars(rating) {
	// console.log('Rating....', rating);
	// ratingNumber = Number(rating);
	// console.log("Rating....", ratingNumber);

	let stars = '';
	for (let i = 1; i <= rating; i++) {
		stars += `<span class="valStar" data-rating="${i}">&#9733;</span>`;
	}
	// console.log('Stars....', stars);
	return stars;
}

function showReviews(reviews) {
	$('.showCmt').html($('<h3>Phản hồi của người dùng</h3>'));
	reviews.forEach((review) => {
		let date = new Date(review.created_at);
		let formattedDate = `${date.getHours()}h${date.getMinutes()}p, ${date.getDate()}/${
			date.getMonth() + 1
		}/${date.getFullYear()}`;
		let stars = generateStars(review.rating);
		$('.showCmt').append(
			$(`
			<div class="itemCmt">
				<div class="product-rating" id="productRating">
				${stars}
				</div>
				<div id="titleCmt">
					<span id="showName">${review.username}</span>
					<span id="showTime">${formattedDate}</span>
				</div>
				<div id="showContent">
					${review.content}
				</div>
			</div>
			`)
		);
	});
}

$('.btn-cart').click(async function () {
	let colors = $('input[type=radio]');
	let color;
	$.each(colors, (idx, element) => {
		if (element.checked) color = element.value;
	});
	let size = $('select').find(':selected').text();
	// console.log(size);
	await addCart(color, size, productId, $('input[type=number]').val());
});

// $(document).on('submit', '#commentt', function (e) {
// 	e.preventDefault();
// 	let content = $('#contentCmt').val();
// 	let rating = $('#selectedRating').data('result');

// 	console.log('Content....', content);
// 	console.log('Rating....', rating);
// });

$('#commentt').on('submit', function (e) {
	e.preventDefault();

	let token = Cookies.get('token');

	if (!token) location.href = '../Signin/index.html';

	var contentCmt = $('#contentCmt').val();
	var selectedRating = $('#selectedRating').data('result');

	var formData = {
		content: contentCmt,
		rating: selectedRating,
	};
	console.log('Form data....', formData);
	var params = new URLSearchParams(window.location.search);
	var id = params.get('id');
	$.ajax({
		url: 'http://localhost/WebProject/api/review/' + id,
		type: 'POST',
		data: JSON.stringify(formData),
		headers: {
			'Authorization': `Bearer ${token}`
		},
		contentType: 'application/json',
		success: function (response) {
			console.log(response);
			alert('Đánh giá thành công');
			// You can add more actions here based on the response
			location.reload();
		},
		error: function (error) {
			console.log(error);
		},
	});
});

// document.getElementById('commentt').addEventListener('submit', function(e) {
//     e.preventDefault();
//     var contentCmt = document.getElementById('contentCmt').value;
//     var selectedRating = document.getElementById('selectedRating').getAttribute('data-result');

//     var formData = {
//         content: contentCmt,
//         rating: selectedRating
//     };
// 	console.log('Form data....', formData);
//     // fetch('http://localhost/WebProject/api/comment', {
//     //     method: 'POST',
//     //     headers: {
//     //         'Content-Type': 'application/json'
//     //     },
//     //     body: JSON.stringify(formData)
//     // })
//     // .then(response => response.json())
//     // .then(data => console.log(data))
//     // .catch((error) => {
//     //     console.error('Error:', error);
//     // });
// });
