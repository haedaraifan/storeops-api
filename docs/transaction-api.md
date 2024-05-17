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
      "quantity": 2
    },
    {
      "id": 2,
      "quantity": 5
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

**Role :** Admin | Kasir

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "id": 1,
      "date": "2 Mei 2024",
      "type": "Pengeluaran",
      "status": "Lunas",
      "purchase_price": 100000, // nullable
      "selling_price": null // nullable
    },
    {
      "id": 2,
      "date": "2 Mei 2024",
      "type": "Penjualan",
      "status": "Lunas",
      "purchase_price": null, // nullable
      "selling_price": 120000 // nullable
    }
  ]
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```
