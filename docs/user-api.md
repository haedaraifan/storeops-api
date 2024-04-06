# User API Spec

## Register User

**Endpoint :** POST /api/users/register

**Request Body :**

```json
{
  "email": "john@gmail.com",
  "password": "rahasia",
  "name": "john doe"
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "john doe"
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

**Endpoint :** POST /api/users/login

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

## Get User

**Endpoint :** GET /api/users/me

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "john doe",
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## Update User

**Endpoint :** PATCH /api/users/me

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "name": "doe", // optional
  "password": "rahasia123" // optional
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "email": "john@gmail.com",
    "name": "doe"
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

**Endpoint :** POST /api/users/logout

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
