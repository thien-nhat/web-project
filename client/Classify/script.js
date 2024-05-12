var products = [];
var filteredProducts = [];
var categories = [];
var params = new URLSearchParams(location.search);

var filterCate = (product) => true;
var filterPrice = (product) => true;

$(document).ready(async () => {
	await getProducts();
	showProducts();

	await getBrandCategories();
	showClassifyCategories();

	$('input[type=radio]').click((e) => {
		switch (e.target.value) {
			case 'all':
				filterPrice = (product) => true;
				break;
			case '0-200':
				filterPrice = (product) => product.unitPrice < 200000;
				break;
			case '200-500':
				filterPrice = (product) =>
					product.unitPrice >= 200000 && product.unitPrice < 500000;
				break;
			case '500-1500':
				filterPrice = (product) =>
					product.unitPrice >= 500000 && product.unitPrice < 1500000;
				break;
			case '1500-2500':
				filterPrice = (product) =>
					product.unitPrice >= 1500000 && product.unitPrice < 2500000;
				break;
			case '2500':
				filterPrice = (product) => product.unitPrice >= 2500000;
				break;

			default:
				break;
		}
		$('#criteria-price .criteria-item-text').text(e.target.dataset.text);
		showProducts();
	});
});
// Lấy danh sách các ô checkbox đã được chọn
function getCheckedCheckboxes() {
	const checkedCheckboxes = $(
		'.high-brands__item-icon input[type="checkbox"]:checked'
	);
	return checkedCheckboxes;
}

// Hàm để duyệt và xử lý thông tin của các ô checkbox đã chọn
function processCheckedCheckboxes() {
	const checkedCheckboxes = getCheckedCheckboxes();
	checkedCheckboxes.each(function () {
		const id = $(this).attr('id');
		const categoryId = parseInt(id.split('-')[1]);
		const categoryName = $(this)
			.closest('.high-brands__item-icon')
			.find('.high-brands__item-text')
			.text();
		console.log('Category ID:', categoryId);
		console.log('Category Name:', categoryName);
	});
}

// Hàm để lấy danh sách categoryId từ các ô checkbox đã chọn
function getSelectedCategoryIds() {
	const selectedCategoryIds = [];
	const checkedCheckboxes = getCheckedCheckboxes();
	checkedCheckboxes.each(function () {
		const id = $(this).attr('id');
		const categoryId = parseInt(id.split('-')[1]);
		selectedCategoryIds.push(categoryId);
	});
	selectedCategoryIds.push(0);
	return selectedCategoryIds;
}

// Hàm để thực hiện truy vấn đến URL cụ thể với danh sách categoryId đã chọn
function queryProductsByCategoryIds() {
	const selectedCategoryIds = getSelectedCategoryIds();
	const brandIdString = selectedCategoryIds.join(',');
	console.log('categoryIdString', brandIdString);
	categoryIdString = params.get('categoryId')
	const apiUrl = `http://localhost/WebProject/api/category/${categoryIdString}/products?brandIds=${brandIdString}`;

	// Thực hiện truy vấn AJAX
	$.ajax({
		url: apiUrl,
		success: function (data) {
			// Xử lý dữ liệu trả về (nếu cần)
			console.log(data);
			products = data;
			showProducts();
		},
		error: function (error) {
			console.log('Error:', error);
		},
	});
}

async function getBrandCategories() {
	try {
		await $.ajax(
			`http://localhost/WebProject/api/category/${params.get(
				'categoryId'
			)}/brands`,
			{
				success: (data) => {
					// products = data.payload;
					// products = data;
					// console.log(data);
					categories = data;
					const highBrandsListLeft = $('.high-brands__list-left');
					const highBrandsListRight = $('.high-brands__list-right');
					highBrandsListLeft.empty();
					highBrandsListRight.empty();
					// Calculate the midpoint to split the categories into two parts
					const midpoint = Math.ceil(categories.length / 2);

					// Iterate over the categories array
					categories.forEach((category, index) => {
						// Create the checkbox element
						const checkbox = $('<input>').attr({
							type: 'checkbox',
							id: `category-${category.id}`,
							checked: 'checked',
						});

						// Create the label element containing the checkbox and category name
						const label = $('<label>')
							.addClass('high-brands__item-icon')
							.append(
								checkbox,
								$('<span>').addClass('checkmark'),
								$('<div>')
									.addClass('high-brands__item-text')
									.text(category.name)
							);

						// Create the container for the high-brands item and append the label
						const highBrandsItem = $('<div>')
							.addClass('high-brands__item')
							.append(label);

						// Determine which container to append the high-brands item to based on the index
						if (index < midpoint) {
							highBrandsListLeft.append(highBrandsItem);
						} else {
							highBrandsListRight.append(highBrandsItem);
						}
					});
				},
				error: (err) => {
					console.log(err);
				},
			}
		);
	} catch (error) {}
	// Gọi hàm processCheckedCheckboxes() mỗi khi có sự kiện thay đổi trên các ô checkbox
	$('.high-brands__item-icon input[type="checkbox"]').on('change', function () {
		processCheckedCheckboxes();
		queryProductsByCategoryIds();
	});
}

async function getProducts() {
	try {
		await $.ajax(
			`http://localhost/WebProject/api/category/${params.get(
				'categoryId'
			)}/products`,
			{
				success: (data) => {
					// products = data.payload;
					products = data;
				},
				error: (err) => {
					console.log(err);
				},
			}
		);
	} catch (error) {}
}

function showProducts() {
	// filterProducts();
	filteredProducts = products;
	filteredProducts.length = 6;
	let html = '';
	$('.result-num').text(filteredProducts.length);

	filteredProducts.forEach((product) => {
		// product.images[0] = null;
		product.priceBeforeDiscount = product.price;
		product.priceAfterDiscount =
			product.price - product.discount * product.price;

		html += `
            <div class="grid__column-2-5 m-4">
                <div class="product-list-item">
                    <div class="product-list-item__img" style="background-image: url(${
											product.url
										});"></div>
                    <div class="product-list-item__overlay">
                        <div class="overlay-content">
                            <div class="overlay-favor">
                                <a href="">
                                    <i class="overlay-favor-icon fa-regular fa-heart"></i>
                                </a>
                            </div>

                            <div class="overlay-add" onclick="addCart(${
															product.id
														}, 1)">
                                <a>
                                    <i class="overlay-add-icon fa-solid fa-cart-shopping"></i>
                                </a>
                            </div>

                            <div class="overlay-detail">
                                <a href="../Detail/index.html?id=${product.id}">
                                    <i class="overlay-detail-icon fa-solid fa-info"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="product-list-item__name" onclick="(()=>{
						location.href='../Detail/index.html?id=${product.id}';
					})()">
                        ${product.name}
                    </div>
                    <div class="product-list-item__price">
						<div >
						<span class="priceBeforeDiscount">
                        ${numberWithSeparator(
													product.priceBeforeDiscount,
													','
												)} VNĐ		
						</span>				
						<span id="valDisc">${product.discount * 100}% OFF</span>

						</div>
						<div class="priceAfterDiscount">
						${numberWithSeparator(product.priceAfterDiscount, ',')} VNĐ
						</div>
                    </div>
                </div>
            </div>
        `;
	});
	$('.product-list .grid__row').html(html);
}

function showClassifyCategories() {
	$('.classify-list').html('');
	categories.forEach((category) => {
		$('.classify-list').append(`
            <li class="classify-item" onclick="changeQueryCategory(${
							category.id
						})">
                <label class="classify-item-icon">
                    <input
                        type="radio"
                        ${
													category.id == params.get('categoryId')
														? 'checked'
														: ''
												}
                        name="classify"
                    />
                    <span
                        class="checkmark-classify"
                    ></span>
                    <div class="classify-item-text">
                        ${category.name}
                    </div>
                </label>
            </li>
        `);
		if (category.id == params.get('categoryId'))
			$('#criteria-category .criteria-item-text').text(category.name);
	});
}

function changeQueryCategory(categoryId) {
	params.set('categoryId', categoryId);
	location.search = params.toString();
}

function filterProducts() {
	filteredProducts = products.filter(
		(product) => filterCate(product) && filterPrice(product)
	);
}
