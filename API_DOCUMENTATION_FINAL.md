# Dokumentasi API Artikel - Updated dengan Pagination

## Base URL

```
http://localhost:8080/api
```

## Endpoints

### 1. Mendapatkan Semua Artikel (dengan Pagination)

-   **URL**: `/articles`
-   **Method**: `GET`
-   **Query Parameters**:
    -   `page` (optional): Halaman yang diinginkan (default: 1)
    -   `per_page` (optional): Jumlah item per halaman (default: 10, max: 50)
    -   `search` (optional): Kata kunci pencarian (cari di judul, deskripsi, penulis)
    -   `tag_id` (optional): Filter berdasarkan tag ID
    -   `sort_by` (optional): Field untuk sorting (default: created_at)
    -   `sort_order` (optional): Arah sorting asc/desc (default: desc)

**Contoh Request**:

```bash
GET /api/articles?page=1&per_page=5&search=teknologi&tag_id=1&sort_by=judul&sort_order=asc
```

-   **Response**:

```json
{
    "success": true,
    "message": "Articles retrieved successfully",
    "data": [
        {
            "id": 1,
            "image": "articles/filename.jpg",
            "image_url": "http://localhost:8080/storage/articles/filename.jpg",
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
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 5,
        "total": 15,
        "from": 1,
        "to": 5,
        "has_more": true
    },
    "links": {
        "first": "http://localhost:8080/api/articles?page=1",
        "last": "http://localhost:8080/api/articles?page=3",
        "prev": null,
        "next": "http://localhost:8080/api/articles?page=2"
    }
}
```

### 2. Mendapatkan Artikel Berdasarkan ID

-   **URL**: `/articles/{id}`
-   **Method**: `GET`
-   **Response**: Sama seperti format di atas untuk single item (tanpa pagination)

### 3. Mendapatkan Semua Tags

-   **URL**: `/articles/tags`
-   **Method**: `GET`
-   **Response**:

```json
{
    "success": true,
    "message": "Tags retrieved successfully",
    "data": [
        {
            "id": 1,
            "nama": "Teknologi"
        },
        {
            "id": 2,
            "nama": "Kesehatan"
        }
    ]
}
```

### 4. Mendapatkan Artikel Berdasarkan Tag (dengan Pagination)

-   **URL**: `/articles/tag/{tagId}`
-   **Method**: `GET`
-   **Query Parameters**: Sama seperti endpoint utama
-   **Response**: Sama seperti endpoint utama dengan pagination

### 5. Membuat Artikel Baru (dengan File Upload)

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

### 6. Update Artikel (dengan File Upload)

-   **URL**: `/articles/{id}`
-   **Method**: `PUT`
-   **Content-Type**: `multipart/form-data`
-   **Request Body**: Sama seperti create

### 7. Hapus Artikel

-   **URL**: `/articles/{id}`
-   **Method**: `DELETE`
-   **Response**:

```json
{
    "success": true,
    "message": "Article deleted successfully"
}
```

## Frontend Implementation Examples

### React/Next.js Example

```javascript
// Hook untuk pagination
const [articles, setArticles] = useState([]);
const [pagination, setPagination] = useState({});
const [loading, setLoading] = useState(false);

const fetchArticles = async (page = 1, search = "", tagId = "") => {
    setLoading(true);
    try {
        const params = new URLSearchParams({
            page: page.toString(),
            per_page: "10",
            ...(search && { search }),
            ...(tagId && { tag_id: tagId }),
        });

        const response = await fetch(`/api/articles?${params}`);
        const data = await response.json();

        if (data.success) {
            setArticles(data.data);
            setPagination(data.pagination);
        }
    } catch (error) {
        console.error("Error fetching articles:", error);
    } finally {
        setLoading(false);
    }
};

// Pagination component
const Pagination = ({ pagination, onPageChange }) => {
    return (
        <div className="pagination">
            <button
                disabled={pagination.current_page === 1}
                onClick={() => onPageChange(pagination.current_page - 1)}
            >
                Previous
            </button>

            <span>
                Page {pagination.current_page} of {pagination.last_page}
            </span>

            <button
                disabled={!pagination.has_more}
                onClick={() => onPageChange(pagination.current_page + 1)}
            >
                Next
            </button>
        </div>
    );
};
```

### Vue.js Example

```javascript
// Composition API
const { ref, onMounted } = Vue;

const articles = ref([]);
const pagination = ref({});
const currentPage = ref(1);
const searchQuery = ref("");

const fetchArticles = async () => {
    const params = new URLSearchParams({
        page: currentPage.value,
        per_page: 10,
        search: searchQuery.value,
    });

    const response = await fetch(`/api/articles?${params}`);
    const data = await response.json();

    articles.value = data.data;
    pagination.value = data.pagination;
};

const nextPage = () => {
    if (pagination.value.has_more) {
        currentPage.value++;
        fetchArticles();
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
        fetchArticles();
    }
};
```

## Error Response

```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error information"
}
```

## Validation Error (422)

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

## File Upload Specifications

-   **Allowed formats**: JPEG, PNG, JPG, GIF
-   **Maximum size**: 2MB (2048 KB)
-   **Storage location**: `storage/app/public/articles/`
-   **Access URL**: `http://domain.com/storage/articles/filename.ext`
-   **Naming**: Laravel akan otomatis generate nama file unik
-   **Auto-delete**: File lama dihapus otomatis saat update/delete artikel

---

# Dokumentasi API Katalog

## Base URL

```
http://localhost:8080/api/katalogs
```

## Endpoints

### 1. Mendapatkan Semua Katalog

-   **URL**: `/katalogs`
-   **Method**: `GET`
-   **Response**:

```json
{
    "success": true,
    "message": "Data katalog berhasil diambil",
    "data": [
        {
            "id": 1,
            "nama": "Produk Unggulan",
            "deskripsi": "Berbagai produk unggulan dari BUMDes yang berkualitas tinggi dan terpercaya untuk memenuhi kebutuhan masyarakat",
            "created_at": "2025-08-09T19:45:00.000000Z",
            "updated_at": "2025-08-09T19:45:00.000000Z"
        },
        {
            "id": 2,
            "nama": "Layanan Jasa",
            "deskripsi": "Beragam layanan jasa profesional yang disediakan BUMDes untuk mendukung kebutuhan bisnis dan pribadi masyarakat",
            "created_at": "2025-08-09T19:45:00.000000Z",
            "updated_at": "2025-08-09T19:45:00.000000Z"
        }
    ]
}
```

### 2. Mendapatkan Katalog untuk Halaman Home (4 Data)

-   **URL**: `/katalogs/home`
-   **Method**: `GET`
-   **Description**: Khusus untuk menampilkan 4 katalog di halaman home, hanya mengembalikan id, nama, dan deskripsi
-   **Response**:

```json
{
    "success": true,
    "message": "Data katalog untuk home berhasil diambil",
    "data": [
        {
            "id": 1,
            "nama": "Produk Unggulan",
            "deskripsi": "Berbagai produk unggulan dari BUMDes yang berkualitas tinggi dan terpercaya untuk memenuhi kebutuhan masyarakat"
        },
        {
            "id": 2,
            "nama": "Layanan Jasa",
            "deskripsi": "Beragam layanan jasa profesional yang disediakan BUMDes untuk mendukung kebutuhan bisnis dan pribadi masyarakat"
        },
        {
            "id": 3,
            "nama": "Produk Digital",
            "deskripsi": "Inovasi produk digital terdepan untuk mendukung transformasi digital di era modern"
        },
        {
            "id": 4,
            "nama": "Kemitraan Bisnis",
            "deskripsi": "Program kemitraan strategis untuk mengembangkan bisnis bersama dan menciptakan nilai tambah"
        }
    ]
}
```

### 3. Mendapatkan Katalog Berdasarkan Slug

-   **URL**: `/katalogs/{slug}`
-   **Method**: `GET`
-   **Parameter**: `slug` (string) - Slug unik dari katalog (contoh: "produk-unggulan")
-   **Response**:

```json
{
    "success": true,
    "message": "Data katalog berhasil diambil",
    "data": {
        "id": 1,
        "nama": "Produk Unggulan",
        "slug": "produk-unggulan",
        "deskripsi": "Berbagai produk unggulan dari BUMDes yang berkualitas tinggi dan terpercaya untuk memenuhi kebutuhan masyarakat",
        "created_at": "2025-08-09T19:45:00.000000Z",
        "updated_at": "2025-08-09T19:45:00.000000Z"
    }
}
```

### 4. Membuat Katalog Baru

-   **URL**: `/katalogs`
-   **Method**: `POST`
-   **Request Body**:

```json
{
    "nama": "Nama Katalog",
    "deskripsi": "Deskripsi katalog yang detail"
}
```

-   **Response**:

```json
{
    "success": true,
    "message": "Katalog berhasil dibuat",
    "data": {
        "id": 7,
        "nama": "Nama Katalog",
        "slug": "nama-katalog",
        "deskripsi": "Deskripsi katalog yang detail",
        "created_at": "2025-08-09T20:00:00.000000Z",
        "updated_at": "2025-08-09T20:00:00.000000Z"
    }
}
```

### 5. Mengupdate Katalog

-   **URL**: `/katalogs/{slug}`
-   **Method**: `PUT`
-   **Parameter**: `slug` (string) - Slug unik dari katalog yang akan diupdate
-   **Request Body**:

```json
{
    "nama": "Nama Katalog Updated",
    "deskripsi": "Deskripsi katalog yang sudah diupdate"
}
```

-   **Response**:

```json
{
    "success": true,
    "message": "Katalog berhasil diupdate",
    "data": {
        "id": 1,
        "nama": "Nama Katalog Updated",
        "slug": "nama-katalog-updated",
        "deskripsi": "Deskripsi katalog yang sudah diupdate",
        "created_at": "2025-08-09T19:45:00.000000Z",
        "updated_at": "2025-08-09T20:05:00.000000Z"
    }
}
```

### 6. Menghapus Katalog

-   **URL**: `/katalogs/{slug}`
-   **Method**: `DELETE`
-   **Parameter**: `slug` (string) - Slug unik dari katalog yang akan dihapus
-   **Response**:

```json
{
    "success": true,
    "message": "Katalog berhasil dihapus"
}
```

## Error Responses

### Not Found (404)

```json
{
    "success": false,
    "message": "Katalog tidak ditemukan",
    "error": "Error details"
}
```

### Server Error (500)

```json
{
    "success": false,
    "message": "Gagal mengambil data katalog",
    "error": "Detailed error information"
}
```

### Validation Error (422)

```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "nama": ["The nama field is required."],
        "deskripsi": ["The deskripsi field is required."]
    }
}
```

## Validation Rules

-   **nama**: Required, string, maximum 255 characters
-   **deskripsi**: Required, string (text field)

## Catatan Tambahan

### Slug Generation

-   Slug dibuat otomatis dari nama katalog menggunakan format kebab-case
-   Contoh: "Produk Unggulan" menjadi "produk-unggulan"
-   Slug harus unik dalam database
-   Slug digunakan untuk mengakses detail katalog melalui API

### Endpoint Khusus

-   `/katalogs/home` - Khusus untuk halaman home, mengembalikan 4 katalog terbaru dengan field terbatas (id, nama, deskripsi)
-   `/katalogs/{slug}` - Menggunakan slug sebagai identifier, bukan ID numerik
