# User API Spec

## Register User

**Endpoint :** POST /api/register

**Role :** Admin

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "email": "john@gmail.com",
  "password": "rahasia",
  "name": "john",
  "role": "Kasir"
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 11,
    "email": "john@gmail.com",
    "name": "john",
    "role": "Kasir"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Email telah terdatar."
}
```

## Login User

**Endpoint :** POST /api/login

**Request Body :**

```json
{
  "name": "john",
  "password": "rahasia"
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 11,
    "email": "john@gmail.com",
    "name": "john",
    "role": "Kasir",
    "token": "token-123"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Email atau password salah."
}
```

## Get User Profile

**Endpoint :** GET /api/me

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "id": 11,
    "email": "john@gmail.com",
    "name": "john",
    "role": "Kasir"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Update User Profile

**Endpoint :** PATCH /api/me

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "doe", // optional
  "email": "doe@gmail.com", // optional
  "password": "rahasia123" // optional
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 11,
    "email": "doe@gmail.com",
    "name": "doe",
    "role": "Kasir"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Logout User

**Endpoint :** DELETE /api/logout

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "message": "Logout berhasil."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```
