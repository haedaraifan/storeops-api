# Transaction API Spec

## Add Expense

**Endpoint :** POST /api/transactions/expense

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "date": "2 Mei 2024",
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
  "date": "2 Mei 2024",
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
      "purchase_price": 100000,
      "selling_price": null
    },
    {
      "id": 2,
      "date": "2 Mei 2024",
      "type": "Penjualan",
      "status": "Lunas",
      "purchase_price": null,
      "selling_price": 120000
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
