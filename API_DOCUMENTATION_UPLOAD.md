# Dokumentasi API Artikel (Updated - File Upload)

## Base URL

```
http://localhost:8080/api
```

## Perubahan Penting

-   Field `image` sekarang menggunakan **file upload** bukan URL string
-   Response API akan menyertakan `image_url` untuk akses gambar langsung
-   File gambar disimpan di `storage/app/public/articles/`
-   Gambar dapat diakses melalui URL: `http://localhost:8080/storage/articles/filename.jpg`

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
            "image": "articles/1691234567_image.jpg",
            "image_url": "http://localhost:8080/storage/articles/1691234567_image.jpg",
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
-   **Response**: Sama seperti format di atas untuk single item

### 3. Membuat Artikel Baru (dengan File Upload)

-   **URL**: `/articles`
-   **Method**: `POST`
-   **Content-Type**: `multipart/form-data`
-   **Request Body**:

```
judul: "Judul Artikel" (required, string, max 255)
deskripsi: "Deskripsi artikel" (required, string)
tag_id: 1 (required, exists in tags table)
penulis: "Nama Penulis" (required, string, max 255)
image: [file] (optional, image file: jpeg,png,jpg,gif, max 2MB)
```

**Contoh menggunakan cURL**:

```bash
curl -X POST "http://localhost:8080/api/articles" \
  -H "Accept: application/json" \
  -F "judul=Test Artikel Baru" \
  -F "deskripsi=Ini adalah deskripsi untuk artikel test" \
  -F "tag_id=1" \
  -F "penulis=Test Author" \
  -F "image=@/path/to/your/image.jpg"
```

**Contoh menggunakan JavaScript (FormData)**:

```javascript
const formData = new FormData();
formData.append("judul", "Test Artikel Baru");
formData.append("deskripsi", "Ini adalah deskripsi untuk artikel test");
formData.append("tag_id", "1");
formData.append("penulis", "Test Author");
formData.append("image", fileInput.files[0]); // dari input file

fetch("http://localhost:8080/api/articles", {
    method: "POST",
    headers: {
        Accept: "application/json",
    },
    body: formData,
})
    .then((response) => response.json())
    .then((data) => console.log(data));
```

-   **Response**:

```json
{
    "success": true,
    "message": "Article created successfully",
    "data": {
        "id": 6,
        "image": "articles/1691234567_image.jpg",
        "image_url": "http://localhost:8080/storage/articles/1691234567_image.jpg",
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

### 4. Update Artikel (dengan File Upload)

-   **URL**: `/articles/{id}`
-   **Method**: `PUT` atau `POST` dengan `_method=PUT`
-   **Content-Type**: `multipart/form-data`
-   **Request Body**: Sama seperti create

**Catatan**: Jika file gambar baru diupload, gambar lama akan otomatis dihapus dari storage.

**Contoh menggunakan cURL**:

```bash
curl -X PUT "http://localhost:8080/api/articles/1" \
  -H "Accept: application/json" \
  -F "judul=Judul yang Diupdate" \
  -F "deskripsi=Deskripsi yang diupdate" \
  -F "tag_id=2" \
  -F "penulis=Penulis Updated" \
  -F "image=@/path/to/new/image.jpg"
```

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

**Catatan**: Ketika artikel dihapus, file gambar terkait juga akan otomatis dihapus dari storage.

### 6. Mendapatkan Artikel Berdasarkan Tag

-   **URL**: `/articles/tag/{tagId}`
-   **Method**: `GET`
-   **Response**: Sama seperti get all articles dengan filter tag

## Error Response

### Validation Error (422)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "image": [
            "The image must be an image.",
            "The image may not be greater than 2048 kilobytes."
        ],
        "judul": ["The judul field is required."]
    }
}
```

### General Error (500/404)

```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error information"
}
```

## File Upload Specifications

-   **Allowed formats**: JPEG, PNG, JPG, GIF
-   **Maximum size**: 2MB (2048 KB)
-   **Storage location**: `storage/app/public/articles/`
-   **Access URL**: `http://domain.com/storage/articles/filename.ext`
-   **Naming**: Laravel akan otomatis generate nama file unik
-   **Auto-delete**: File lama dihapus otomatis saat update/delete artikel
