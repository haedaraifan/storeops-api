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

## Restock Product

**Endpoint :** POST /api/products/:productId/restock

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "quantity": 45,
  "destination_address": "Besito",
  "supplier_name": "Jamal",
  "supplier_address": "Gang 10",
  "supplier_phone": "0811111",
  "shipping_method": "Mobil",
  "payment_method": "Cash"
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

**Endpoint :** GET /api/products/restock

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
        "id": 1,
        "date": "28 Mei, 2024",
        "product": {
            "name": "Kayu",
            "category": "Pcs",
            "purchase_price": 120000,
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
        "date": "29 Mei, 2024",
        "product": {
            "name": "Paku",
            "category": "Pcs",
            "purchase_price": 800,
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
  ]
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```
