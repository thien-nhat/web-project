<?php

// Định tuyến API
$routes['api/login']['POST'] = 'auth/login'; // API login
$routes['api/logout'] = 'auth/logout'; // API đăng xuất
$routes['api/register']['POST'] = 'auth/register'; // API register

// User Routes
$routes['api/user']['GET'] = 'user/getAllUsers';
$routes['api/user']['POST'] = 'user/createUser';
$routes['api/user/:id']['GET'] = 'user/getUser/$1';
$routes['api/user/:id']['PUT'] = 'user/updateUser/$1';
$routes['api/user/:id']['DELETE'] = 'user/deleteUser/$1';

// Product Routes
$routes['api/product']['GET'] = 'product/getAllProducts';
$routes['api/product']['POST'] = 'product/createProduct';
$routes['api/product/:id']['GET'] = 'product/getProduct/$1';
$routes['api/product/:id']['PUT'] = 'product/updateProduct/$1';
$routes['api/product/:id']['DELETE'] = 'product/deleteProduct/$1';

$routes['api/product/flash-sale']['GET'] = 'product/saleProducts';
$routes['api/product/best-seller']['GET'] = 'product/bestProduct';
$routes['api/product/top-product']['GET'] = 'product/topProduct';
$routes['api/product/new-product']['GET'] = 'product/newProduct';

$routes['api/product-image/:id']['POST'] = 'product/createProductImage/$1';

// Category Routes
$routes['api/category']['GET'] = 'category/getAllCategories';
$routes['api/category']['POST'] = 'category/createCategory';
$routes['api/category/:id']['GET'] = 'category/getCategory/$1'; //get product and brand of this category, query to brand = ?
$routes['api/category/:id']['PUT'] = 'category/updateCategory/$1'; //get product and brand of this category, query to brand = ?
$routes['api/category/:id']['DELETE'] = 'category/deleteCategory/$1'; //get product and brand of this category, query to brand = ?

// Brand Routes
$routes['api/brand']['GET'] = 'brand/getAllBrands';
$routes['api/brand/:id']['GET'] = 'brand/getBrand/$1'; //get product and brand of this category, query to brand = ?
$routes['api/brand']['POST'] = 'brand/createBrand'; //get product and brand of this category, query to brand = ?
$routes['api/brand/:id']['PUT'] = 'brand/updateBrand/$1'; //get product and brand of this category, query to brand = ?
$routes['api/brand/:id']['DELETE'] = 'brand/deleteBrand/$1'; //get product and brand of this category, query to brand = ?


// Review Routes
$routes['api/review']['GET'] = 'review/getAllReviews';
// $routes['api/review']['POST'] = 'review/createReview';
$routes['api/review/:id']['POST'] = 'review/createReview/$1';
$routes['api/review/:id']['GET'] = 'review/getReview/$1';

// Cart Routes
$routes['api/cart']['GET'] = 'cart/getAllCarts';
$routes['api/cart']['POST'] = 'cart/createCart';
$routes['api/mycart']['GET'] = 'cart/getMyCart';
$routes['api/cart/:id']['GET'] = 'cart/getCart/$1'; //get cart by user id
$routes['api/cart/:id']['PATCH'] = 'cart/updateCart/$1';
$routes['api/cart/:id']['DELETE'] = 'cart/deleteCart/$1';

// Order Routes
$routes['api/order']['GET'] = 'order/getAllOrders';
$routes['api/order']['POST'] = 'order/createOrder';
$routes['api/myorder']['GET'] = 'order/getMyOrderId';
$routes['api/order/:id']['GET'] = 'order/getOrder/$1'; //get cart by user id
$routes['api/order/:id']['PATCH'] = 'order/updateOrder/$1';

// Get category by brands and products
$routes['api/category/:id/brands']['GET'] = 'category/getCategoryBrands/$1';
$routes['api/category/:id/products']['GET'] = 'category/getCategoryProducts/$1';
$routes['api/category/:id/brand/:id/products']['GET'] = 'category/getProductsOfBrandAndCategory/$1/$2';

// Ask Support
$routes['api/asksupport']['GET'] = 'askSupport/selectAll';
$routes['api/asksupport']['POST'] = 'askSupport/createPost';

