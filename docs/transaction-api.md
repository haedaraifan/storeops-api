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
      "is_checked": true // optional
    },
    {
      "id": 2,
      "quantity": 5,
      "is_checked": false // optional
    }
  ],
  "discount": 10000, // optional
  "additional_cost": 2000, // optional
  "status": "Lunas",
  "option": 1, // optional [1: Diambil, 2: Dikirim]
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

**Query Parameter :**
- paginate: true | false

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

**Query Parameter :**
- search = customer name, date
- range : daily | weekly | monthly
- paid: true | false
- paginate: true | false

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "id": 2,
      "invoice": "K344A259640H130920U4",
      "date": "Kamis, 2 Mei 2024",
      "type": "Penjualan",
      "status": "Lunas",
      "is_finished": true,
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
      ],
      "note": "ini note1" // nullable
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
    "invoice": "K344A259640H130920U4",
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
    ],
    "note": "ini note1" // nullable
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
- category
- year
- month
- sort : asc | desc
- paginate: true | false

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
  	"range": "Agustus 2024",
  	"products": [
  	  {
  	  	"id": 12,
  	  	"name": "Paku payung",
        "category": "Paku dan Sekrup",
  	  	"quantity": 18
  	  },
  	  {
  	  	"id": 1,
  	  	"name": "Seng",
        "category": "Material",
  	  	"quantity": 6
  	  }
  	]
  },
  "links": {
  	"first": "https://app.com/api/transactions/income/statistic?page=1",
  	"last": "https://app.com/api/transactions/income/statistic?page=1",
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
  	"path": "https://app.com/api/transactions/income/statistic",
  	"per_page": 20,
  	"to": 16,
  	"total": 16
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
  "products_id": [1, 2]
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

## Finish Transaction

**Endpoint :** POST /api/transactions/finish/:transactionId

**Role :** Admin | Kasir

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "is_finished": true
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
