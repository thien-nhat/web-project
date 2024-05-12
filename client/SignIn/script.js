$(document).ready(async function () {
	$('#loginForm').submit(login);
});

// async function login(e) {
// 	e.preventDefault();

// 	email = $("#email").val();
// 	password = $("#password").val();

// 	try {
// 		let res = await $.ajax({
// 			url: `http://localhost/WebProject/api/login`,
// 			method: "POST",
// 			contentType: "application/json",
// 			data: JSON.stringify({ email, password }),
// 		});
// 		console.log(res);
// 		Cookies.set("token", res.token);
// 		location.href = "../index.html";
// 	} catch (error) {
// 		console.log(error);
// 	}
// }

async function login(e) {
	e.preventDefault();

	let email = $('#email').val();
	let password = $('#password').val();
	console.log(JSON.stringify({ email, password }));
	try {
		let res = await fetch('http://localhost/WebProject/api/login', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ email, password }),
		});

		if (!res.ok) {
			console.log(res);
			// Check if the response has JSON content type
			// Parse the JSON response
			const errorJson = await res.json();

			// Check if the error field exists in the JSON response
			if (errorJson.error) {
				console.log('Error:', errorJson.error);
				throw new Error(`${errorJson.error}`);
				// Handle the error as needed
			} else {
				console.log('Unknown error occurred.');
				// Handle unknown error
			}
		} else {
			let json = await res.json();
			console.log(json);
			// Cookies.set("token", json.token);
			// document.cookie = 'token=' + (json.token || '') + ';path=/';
			document.cookie = `token=${json.token}; path=/;`;
			document.cookie = `username=${json.data.username}; path=/;`;
			// Cookies.set("token", "1234");
			console.log(json.token);
			location.href = '../index.html';
		}
	} catch (error) {
		console.log(error);
		alert(error);
	}
}
