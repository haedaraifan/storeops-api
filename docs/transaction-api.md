# Transaction API Spec

## Add Expense

**Endpoint :** POST /api/transactions/expense

**Role :** Admin | Kasir

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "purchase_price": 100000,
  "status": "Lunas",
  "note": "Bayar listrik" // optional
}
```

**Response Body (Success) :**

```json
{
  "message": "Transaksi berhasil dicatat."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Add Income

**Endpoint :** POST /api/transactions/income

**Role :** Admin | Kasir

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "products": [
    {
      "id": 1,
      "quantity": 2,
      "option": 2, // optional
      "is_checked": true // optional
    },
    {
      "id": 2,
      "quantity": 5,
      "option": 1, // optional
      "is_checked": false // optional
    }
  ],
  "discount": 10000, // optional
  "additional_cost": 2000, // optional
  "status": "Lunas",
  "note": "Penjualan semen dan paku", // optional
  "payment_method": "Cash", // optional
  "customer_name": "Budi", // optional
  "customer_phone": "085111111", // optional
  "customer_address": "Gebog, Kudus" // optional
}
```

**Response Body (Success) :**

```json
{
  "message": "Transaksi berhasil dicatat."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## List Transactions

**Endpoint :** GET /api/transactions

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "id": 1,
      "date": "Kamis, 2 Mei 2024",
      "type": "Pengeluaran",
      "status": "Lunas",
      "purchase_price": 100000, // nullable
      "selling_price": null // nullable
    },
    {
      "id": 2,
      "date": "Kamis, 2 Mei 2024",
      "type": "Penjualan",
      "status": "Lunas",
      "purchase_price": null, // nullable
      "selling_price": 120000 // nullable
    }
  ],
  "links": {
	"first": "https://app.com/api/transactions?page=1",
	"last": "https://app.com/api/transactions?page=2",
	"prev": null,
	"next": "https://app.com/api/transactions?page=2"
  },
  "meta": {
	"current_page": 1,
	"from": 1,
	"last_page": 2,
	"links": [
	  {
	  	"url": null,
	  	"label": "&laquo; Previous",
	  	"active": false
	  },
	  {
	  	"url": "https://app.com/api/transactions?page=1",
	  	"label": "1",
	  	"active": true
	  },
	  {
	  	"url": "https://app.com/api/transactions?page=2",
	  	"label": "2",
	  	"active": false
	  },
	  {
	  	"url": "https://app.com/api/transactions?page=2",
	  	"label": "Next &raquo;",
	  	"active": false
	  }
	],
	"path": "https://app.com/api/transactions",
	"per_page": 10,
	"to": 10,
	"total": 11
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## List Income Transactions

**Endpoint :** GET /api/transactions/income

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "id": 2,
      "date": "Kamis, 2 Mei 2024",
      "type": "Penjualan",
      "status": "Lunas",    
      "purchase_price": null, // nullable
      "selling_price": 160000, // nullable
      "discount": 20000, // nullable
      "additional_cost": 10000, // nullable
      "payment_method": "Cash", // nullable
      "customer":  {
        "name": "Budi", // nullable
        "phone": "08512345", // nullable
        "address": "Besito" // nullable
      },
      "products": [
        {
            "id": 1,
            "name": "Kayu",
            "quantity": 1,
            "price": 150000,
            "option": "Dikirim",
            "is_checked": true
        },
        {
            "id": 2,
            "name": "Paku",
            "quantity": 10,
            "price": 10000,
            "option": "Diambil",
            "is_checked": false
        }
      ]
    }
  ],
  "links": {
	"first": "https://app.com/api/transactions/income?page=1",
	"last": "https://app.com/api/transactions/income?page=1",
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
		"url": "https://app.com/api/transactions/income?page=1",
		"label": "1",
		"active": true
	  },
	  {
		"url": null,
		"label": "Next &raquo;",
		"active": false
	  }
	],
	"path": "https://app.com/api/transactions/income",
	"per_page": 10,
	"to": 10,
	"total": 10
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Detail Income Transaction

**Endpoint :** GET /api/transactions/income/:transactionId

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "id": 2,
    "date": "Kamis, 2 Mei 2024",
    "type": "Penjualan",
    "status": "Lunas",    
    "purchase_price": null, // nullable
    "selling_price": 160000, // nullable
    "discount": 20000, // nullable
    "additional_cost": 10000, // nullable
    "payment_method": "Cash", // nullable
    "customer":  {
      "name": "Budi", // nullable
      "phone": "08512345", // nullable
      "address": "Besito" // nullable
    },
    "products": [
      {
        "id": 1,
        "name": "Kayu",
        "quantity": 1,
        "price": 150000,
        "option": "Dikirim",
        "is_checked": true
      },
      {
        "id": 2,
        "name": "Paku",
        "quantity": 10,
        "price": 10000,
        "option": "Diambil",
        "is_checked": false
      }
    ]
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Transaksi tidak ditemukan."
}
```

## Update Transaction Status

**Endpoint :** POST /api/transactions/status/:transactionId

**Role :** Admin | Kasir

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "status": "Lunas"
}
```

**Response Body (Success) :**

```json
{
  "message": "Transaksi berhasil diperbarui."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Transaksi tidak ditemukan."
}
```

## Transaction Income Statistic

**Endpoint :** GET /api/transactions/income/statistic

**Role :** Admin | Kasir | Gudang

**Query Parameter :**
- year
- month
- sort : asc | desc

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "range": "Agustus 2024",
    "products": {
      "current_page": 1,
      "data": [
        {
          "id": 3,
          "name": "Batako",
          "quantity": 2000
        },
        {
          "id": 2,
          "name": "Paku",
          "quantity": 10000
        }
      ],
      "first_page_url": "https://app.com/api/transactions/income/statistic?page=1",
      "from": 1,
      "last_page": 1,
      "last_page_url": "https://app.com/api/transactions/income/statistic?page=1",
      "links": [
        {
          "url": null,
          "label": "&laquo; Previous",
          "active": false
        },
        {
          "url": "https://app.com/api/transactions/income/statistic?page=1",
          "label": "1",
          "active": true
        },
        {
          "url": null,
          "label": "Next &raquo;",
          "active": false
        }
      ],
      "next_page_url": null,
      "path": "https://app.com/api/transactions/income/statistic",
      "per_page": 20,
      "prev_page_url": null,
      "to": 7,
      "total": 7
    }
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Transaksi tidak ditemukan."
}
```

## Checklist Transaction Product

**Endpoint :** POST /api/transactions/:transactionId/checklist

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "products": [1, 2] // array of product_id
}
```

**Response Body (Success) :**

```json
{
  "message": "Data produk berhasil diperbarui."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Transaksi tidak ditemukan."
}
```
