# User API Spec

## Register User

**Endpoint :** POST /api/register

**Request Body :**

```json
{
  "email": "john@gmail.com",
  "password": "rahasia",
  "name": "john doe",
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "john doe",
    "image": null
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
  "email": "john@gmail.com",
  "password": "rahasia"
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "john doe",
    "image": null,
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

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "john doe",
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

## Update User Profile

**Endpoint :** POST /api/me

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "doe", // optional
  "password": "rahasia123", // optional
  "image": "[image | mimes:jpg,jpeg,png]" // optional
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "doe",
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

## Logout User

**Endpoint :** POST /api/logout

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
