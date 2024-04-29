# Product API Spec

## Add Product

**Endpoint :** POST /api/products

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "kayu jati",
  "quantity": 100,
  "purchase_price": 120000,
  "selling_price": 150000,
  "category": "Pcs",
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
    "category": "Pcs",
    "image": "https://app.com/storage/images/240424012322872092.png"
  }
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
      "category": "Pcs",
      "image": "https://app.com/storage/images/240424012322872092.png"
    },
    {
      "id": 2,
      "name": "paku",
      "quantity": 1000,
      "purchase_price": 800,
      "selling_price": 1000,
      "category": "Pcs",
      "image": "https://app.com/storage/images/240424010517844327.png"
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

## Get Product

**Endpoint :** GET /api/products/:productId

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
    "category": "Pcs",
    "image": "https://app.com/storage/images/240424012322872092.png"
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

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "kayu jati",
  "quantity": 200,
  "purchase_price": 120000,
  "selling_price": 150000,
  "category": "Pcs",
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
    "category": "Pcs",
    "image": "https://app.com/storage/images/240428155054255497.png"
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
