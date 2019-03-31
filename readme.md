# run migrations 
php artisan migrate:refresh
# create product types

POST /api/product-types
{
	"name": "type 1",
	"is_active": true
}

#create product
POST /api/product
{
	"price": 100,
	"product_type_id":1,
	"color": "red",
	"size": "small"
}

#create order (and calculate order price)
POST /api/order

{
	"country_code":"Ru",
	"products":{
		"1": {"id":1, "quantity":1},
		"2": {"id":1, "quantity":1},
		"3": {"id":2, "quantity":2}
	}
}