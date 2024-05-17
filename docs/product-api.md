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

## List Product

**Endpoint :** GET /api/products

**Role :** Admin | Kasir | Gudang

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
      "image": "https://app.com/storage/images/240424012322872092.png" // nullable
    },
    {
      "id": 2,
      "name": "paku",
      "quantity": 1000,
      "purchase_price": 800,
      "selling_price": 1000,
      "category": "Pcs",
      "image": "https://app.com/storage/images/240424010517844327.png" // nullable
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
    "category": "Pcs",
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

**Endpoint :** GET /api/products/add

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "date": "16 Mei. 2024",
      "name": "kayu jati",
      "quantity": 100,
      "purchase_price": 120000,
      "selling_price": 150000
    },
    {
      "date": "17 Mei. 2024",
      "name": "paku",
      "quantity": 1000,
      "purchase_price": 800,
      "selling_price": 1000
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
