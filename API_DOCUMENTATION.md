# Dokumentasi API Artikel

## Base URL

```
http://localhost:8080/api
```

## Endpoints

### 1. Mendapatkan Semua Artikel

-   **URL**: `/articles`
-   **Method**: `GET`
-   **Response**:

```json
{
    "success": true,
    "message": "Articles retrieved successfully",
    "data": [
        {
            "id": 1,
            "image": "articles/ai-technology.jpg",
            "judul": "Perkembangan Teknologi AI di 2025",
            "deskripsi": "Artikel ini membahas tentang perkembangan teknologi...",
            "tag_id": 1,
            "penulis": "John Doe",
            "created_at": "2025-08-08T14:05:03.000000Z",
            "updated_at": "2025-08-08T14:05:03.000000Z",
            "tag": {
                "id": 1,
                "nama": "Teknologi",
                "created_at": "2025-08-08T14:05:03.000000Z",
                "updated_at": "2025-08-08T14:05:03.000000Z"
            }
        }
    ]
}
```

### 2. Mendapatkan Artikel Berdasarkan ID

-   **URL**: `/articles/{id}`
-   **Method**: `GET`
-   **Response**:

```json
{
    "success": true,
    "message": "Article retrieved successfully",
    "data": {
        "id": 1,
        "image": "articles/ai-technology.jpg",
        "judul": "Perkembangan Teknologi AI di 2025",
        "deskripsi": "Artikel ini membahas tentang perkembangan teknologi...",
        "tag_id": 1,
        "penulis": "John Doe",
        "created_at": "2025-08-08T14:05:03.000000Z",
        "updated_at": "2025-08-08T14:05:03.000000Z",
        "tag": {
            "id": 1,
            "nama": "Teknologi",
            "created_at": "2025-08-08T14:05:03.000000Z",
            "updated_at": "2025-08-08T14:05:03.000000Z"
        }
    }
}
```

### 3. Membuat Artikel Baru

-   **URL**: `/articles`
-   **Method**: `POST`
-   **Request Body**:

```json
{
    "judul": "Judul Artikel (required)",
    "deskripsi": "Deskripsi artikel (required)",
    "tag_id": 1,
    "penulis": "Nama Penulis (required)",
    "image": "URL gambar (optional)"
}
```

-   **Response**:

```json
{
    "success": true,
    "message": "Article created successfully",
    "data": {
        "id": 6,
        "image": "articles/test-image.jpg",
        "judul": "Test Artikel Baru",
        "deskripsi": "Ini adalah deskripsi untuk artikel test...",
        "tag_id": 1,
        "penulis": "Test Author",
        "created_at": "2025-08-08T14:08:26.000000Z",
        "updated_at": "2025-08-08T14:08:26.000000Z",
        "tag": {
            "id": 1,
            "nama": "Teknologi",
            "created_at": "2025-08-08T14:05:03.000000Z",
            "updated_at": "2025-08-08T14:05:03.000000Z"
        }
    }
}
```

### 4. Update Artikel

-   **URL**: `/articles/{id}`
-   **Method**: `PUT`
-   **Request Body**:

```json
{
    "judul": "Judul Artikel yang diupdate (required)",
    "deskripsi": "Deskripsi artikel yang diupdate (required)",
    "tag_id": 1,
    "penulis": "Nama Penulis (required)",
    "image": "URL gambar (optional)"
}
```

-   **Response**: Sama seperti create

### 5. Hapus Artikel

-   **URL**: `/articles/{id}`
-   **Method**: `DELETE`
-   **Response**:

```json
{
    "success": true,
    "message": "Article deleted successfully"
}
```

### 6. Mendapatkan Artikel Berdasarkan Tag

-   **URL**: `/articles/tag/{tagId}`
-   **Method**: `GET`
-   **Response**: Sama seperti get all articles

## Error Response

Jika terjadi error, response akan berformat:

```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error information"
}
```

atau untuk validation error:

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    }
}
```
