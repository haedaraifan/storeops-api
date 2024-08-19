# Product API Spec

## Add Product

**Endpoint :** POST /api/products

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "kayu jati",
  "quantity": 100,
  "purchase_price": 120000,
  "selling_price": 150000,
  "unit": "pcs | kg",
  "category": "Kayu",
  "image": "[image | mimes:jpg,jpeg,png]" // optional
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "name": "kayu jati",
    "quantity": 100,
    "purchase_price": 120000,
    "selling_price": 150000,
    "unit": "Pcs",
    "category": "Kayu",
    "image": "https://app.com/storage/images/240424012322872092.png" // nullable
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Import Products

**Endpoint :** POST /api/products/import

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Excel File Row**
- name
- quantity
- purchase_price
- selling_price
- category
- unit

**Request Body :**

```json
{
  "products": "mimes:xlsx,xlx"
}
```

**Response Body (Success) :**

```json
{
  "message": "Data berhasil ditambahkan."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## List Product

**Endpoint :** GET /api/products

**Role :** Admin | Kasir | Gudang

**Query Parameter :**
- category
- paginate: true | false

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "id": 1,
      "name": "kayu jati",
      "quantity": 100,
      "purchase_price": 120000,
      "selling_price": 150000,
      "unit": "Pcs",
      "category": "Kayu",
      "image": "https://app.com/storage/images/240424012322872092.png" // nullable
    },
    {
      "id": 2,
      "name": "paku",
      "quantity": 1000,
      "purchase_price": 800,
      "selling_price": 1000,
      "unit": "Pcs",
      "category": "Payu",
      "image": "https://app.com/storage/images/240424010517844327.png" // nullable
    }
  ],
  "links": {
	  "first": "https://app.com/api/products?page=1",
	  "last": "https://app.com/api/products?page=3",
	  "prev": null,
	  "next": "https://app.com/api/products?page=2"
	},
	"meta": {
	  "current_page": 1,
	  "from": 1,
	  "last_page": 3,
	  "links": [
	  	{
	  	  "url": null,
	  	  "label": "&laquo; Previous",
	  	  "active": false
	  	},
	  	{
	  	  "url": "https://app.com/api/products?page=1",
	  	  "label": "1",
	  	  "active": true
	  	},
	  	{
	  	  "url": "https://app.com/api/products?page=2",
	  	  "label": "2",
	  	  "active": false
	  	},
	  	{
	  	  "url": "https://app.com/api/products?page=3",
	  	  "label": "3",
	  	  "active": false
	  	},
	  	{
	  	  "url": "https://app.com/api/products?page=2",
	  	  "label": "Next &raquo;",
	  	  "active": false
	  	}
	  ],
	  "path": "https://app.com/api/products",
	  "per_page": 10,
	  "to": 10,
	  "total": 22
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Get Product

**Endpoint :** GET /api/products/:productId

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "name": "kayu jati",
    "quantity": 100,
    "purchase_price": 120000,
    "selling_price": 150000,
    "unit": "Pcs",
    "category": "Kayu",
    "image": "https://app.com/storage/images/240424012322872092.png" // nullable
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Produk tidak ditemukan."
}
```

## Update Product

**Endpoint :** POST /api/products/:productId

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "kayu jati",
  "quantity": 200,
  "purchase_price": 120000,
  "selling_price": 150000,
  "unit": "Pcs",
  "category": "Kayu",
  "image": "[image | mimes:jpg,jpeg,png]" // optional
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "name": "kayu jati",
    "quantity": 200,
    "purchase_price": 120000,
    "selling_price": 150000,
    "unit": "Pcs",
    "category": "Kayu",
    "image": "https://app.com/storage/images/240428155054255497.png" // nullable
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Produk tidak ditemukan."
}
```

## Delete Product

**Endpoint :** DELETE /api/products/:productId

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "message": "Produk berhasil dihapus."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Produk tidak ditemukan."
}
```

## List Added Product History

**Endpoint :** GET /api/products/histories/add

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "date": "Kamis, 16 Mei 2024",
      "name": "kayu jati",
      "quantity": 100,
      "unit": "Pcs",
      "category": "kayu",
      "purchase_price": 120000,
      "selling_price": 150000
    },
    {
      "date": "Jumat, 17 Mei 2024",
      "name": "paku",
      "quantity": 1000,
      "unit": "Pcs",
      "category": "Paku",
      "purchase_price": 800,
      "selling_price": 1000
    }
  ],
  "links": {
	"first": "https://app.com/api/products/histories/add?page=1",
	"last": "https://app.com/api/products/histories/add?page=3",
    "prev": null,
	"next": "https://app.com/api/products/histories/add?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "links": [
	  {
	  	"url": null,
	  	"label": "&laquo; Previous",
	  	"active": false
	  },
	  {
	  	"url": "https://app.com/api/products/histories/add?page=1",
	  	"label": "1",
	  	"active": true
	  },
	  {
	  	"url": "https://app.com/api/products/histories/add?page=2",
	  	"label": "2",
	  	"active": false
	  },
	  {
	  	"url": "https://app.com/api/products/histories/add?page=3",
	  	"label": "3",
	  	"active": false
	  },
	  {
	  	"url": "https://app.com/api/products/histories/add?page=2",
	  	"label": "Next &raquo;",
	  	"active": false
	  }
	],
	"path": "https://app.com/api/products/histories/add",
	"per_page": 10,
	"to": 10,
	"total": 24
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Restock Product

**Endpoint :** POST /api/products/:productId/restock

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "quantity": 45,
  "total_purchase_price": 500000,
  "destination_address": "Besito", // nullable
  "supplier_name": "Jamal",
  "supplier_address": "Gang 10",
  "supplier_phone": "0811111",
  "shipping_method": "Mobil",
  "payment_method": "Cash" // nullable
}
```

**Response Body (Success) :**

```json
{
  "message": "Produk berhasil direstok."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Produk tidak ditemukan."
}
```

## List Restocked Product History

**Endpoint :** GET /api/products/histories/restock

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
        "id": 1,
        "date": "Selasa, 28 Mei 2024",
        "product": {
            "name": "Kayu",
            "unit": "Pcs",
            "category": "Kayu",
            "purchase_price": 4800000,
            "total_purchase_price": 500000,
            "selling_price": 150000,
            "new_quantity": 40
        },
        "destination_address": "Besito",
        "payment_method": "Cash",
        "shipping_method": "Mobil pickup",
        "supplier": {
            "name": "Udin",
            "address": "Dawe",
            "phone": "08912345"
        }
    },
    {
        "id": 2,
        "date": "Rabu, 29 Mei 2024",
        "product": {
            "name": "Paku",
            "unit": "Pcs",
            "category": "Paku",
            "purchase_price": 800,
            "total_purchase_price": 80000,
            "selling_price": 1000,
            "new_quantity": 100
        },
        "destination_address": "Besito",
        "payment_method": "Cash",
        "shipping_method": "Mobil pickup",
        "supplier": {
            "name": "Udin",
            "address": "Dawe",
            "phone": "08912345"
        }
    },
  ],
  "links": {
	"first": "https://app.com/api/products/histories/restock?page=1",
	"last": "https://app.com/api/products/histories/restock?page=1",
	"prev": null,
	"next": null
  },
  "meta": {
	"current_page": 1,
	"from": 1,
	"last_page": 1,
	"links": [
	  {
	  	"url": null,
	  	"label": "&laquo; Previous",
	  	"active": false
	  },
	  {
	  	"url": "https://app.com/api/products/histories/restock?page=1",
	  	"label": "1",
	  	"active": true
	  },
	  {
	  	"url": null,
	  	"label": "Next &raquo;",
	  	"active": false
	  }
	],
	"path": "https://app.com/api/products/histories/restock",
	"per_page": 10,
	"to": 5,
	"total": 5
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Recap Products

**Endpoint :** GET /api/products/recap

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "name": "kayu jati",
      "first_quantity": 100,
      "last_quantity": 100,
      "incoming_quantity": 100,
      "outgoing_quantity": 100
    },
    {
      "name": "paku",
      "first_quantity": 200,
      "last_quantity": 100,
      "incoming_quantity": 50,
      "outgoing_quantity": 150
    },
  ],
  "links": {
    "first": "https://app.com/api/products/recap?page=1",
    "last": "https://app.com/api/products/recap?page=3",
    "prev": null,
    "next": "https://app.com/api/products/recap?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "links": [
  	  {
  	    "url": null,
  	    "label": "&laquo; Previous",
  	    "active": false
  	  },
  	  {
  	    "url": "https://app.com/api/products/recap?page=1",
  	    "label": "1",
  	    "active": true
  	  },
  	  {
  	    "url": "https://app.com/api/products/recap?page=2",
  	    "label": "2",
  	    "active": false
  	  },
  	  {
  	    "url": "https://app.com/api/products/recap?page=3",
  	    "label": "3",
  	    "active": false
  	  },
  	  {
  	    "url": "https://app.com/api/products/recap?page=2",
  	    "label": "Next &raquo;",
  	    "active": false
  	  }
    ],
    "path": "https://app.com/api/products/recap",
    "per_page": 10,
    "to": 10,
    "total": 23
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```
