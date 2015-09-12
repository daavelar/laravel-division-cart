# Laravel 5.1 - Cart with Divisions

#Adding products to cart

Cart::division('physical')->add([
	'id' => 'Book',
	'title' => 'The odyssey',
	'price' => 29.99,
	'quantity' => 1, #if the quantity was not informed, the value assumed will be 1
]);

$identifier = Cart::division('digital')->add([
	'id' => 'Mp3',
	'title' => 'Smells Like teen Spirit',
	'price' => 12.99,
	'quantity' => 1,
	'options' => [
		'artist' => [
			'name' => 'Nirvana',
			'id' => 2
		],
		'year' => 1991
	]
]);

#When a product is added to cart the identifier is retrieved

#Removing products of the cart

Cart::remove($identifier);
or
Cart::division('digital')->remove($identifier);

#Retrieving products of the cart
Cart::division('fisicos')->content(); # returns an array of CartItems

#Destroying the Cart

#Just one division
Cart::division('physical')->destroy();
Cart::destroy();


