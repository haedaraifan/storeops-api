# Note API Spec

## Add Note

**Endpoint :** POST /api/notes

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "title": "ini judul",
  "content": "lorem ipsum"
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "title": "ini judul",
    "content": "lorem ipsum",
    "created_at": "Jumat, 17 Mei 2024"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Unauthorized."
}
```

## List Note

**Endpoint :** GET /api/notes

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": [
    {
      "id": 1,
      "title": "ini judul",
      "content": "lorem ipsum",
      "created_at": "Jumat, 17 Mei 2024"
    },
    {
      "id": 2,
      "title": "ini judul hehe",
      "content": "lorem ipsum dolor",
      "created_at": "Jumat, 17 Mei 2024"
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

**Endpoint :** GET /api/notes/:noteId

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "title": "ini judul",
    "content": "lorem ipsum",
    "created_at": "Jumat, 17 Mei 2024"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Catatan tidak ditemukan."
}
```

## Update Note

**Endpoint :** POST /api/notes/:noteId

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Request Body :**

```json
{
  "title": "ini judul yang baru",
  "content": "hehe"
}
```

**Response Body (Success) :**

```json
{
  "data": {
    "id": 1,
    "title": "ini judul yang baru",
    "content": "hehe",
    "created_at": "Jumat, 17 Mei 2024"
  }
}
```

**Response Body (Failed) :**

```json
{
  "error": "Catatan tidak ditemukan."
}
```

## Delete Note

**Endpoint :** DELETE /api/note/:noteId

**Role :** Admin | Kasir | Gudang

**Request Header :**
- AUTHORIZATION : token-123

**Response Body (Success) :**

```json
{
  "message": "Catatan berhasil dihapus."
}
```

**Response Body (Failed) :**

```json
{
  "error": "Catatan tidak ditemukan."
}
```
