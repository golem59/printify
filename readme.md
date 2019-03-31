#Getting started
#DB setup
Fill DB settings in .env file.
# run migrations 
```php artisan migrate:refresh```

#API
## create product types
```
POST /api/product-types
{
	"name": "type 1",
	"is_active": true
}
```
##create product
```
POST /api/product
{
	"price": 100,
	"product_type_id":1,
	"color": "red",
	"size": "small"
}
```

##create order (and calculate order price)
```
POST /api/order

{
	"country_code":"Ru",
	"products":{
		"1": {"id":1, "quantity":1},
		"2": {"id":1, "quantity":1},
		"3": {"id":2, "quantity":2}
	}
}
```

##list all Orders
```
GET /api/order
```

##list all Orders by productType
```
/api/product-types/1/orders
```

#FAQ

##What about case with multiple products with same product_id in order 

Quantity will be calculated as overall on this product id.

##How this order api works with declining when price is less than 10?

Order will be created anyway - if price is less than threshold it would be deactivated.

##What about using middleware for throttle API requests based on country?

I think this is an overkill for this simple API, also throttling for seconds instead of minutes looks pretty awkward 
(with float time, 0.02 minute = 1 second and etc...)

##Do you still have anything to improve for this task?

Actually yes, lots of things, but my evening time quite limited.