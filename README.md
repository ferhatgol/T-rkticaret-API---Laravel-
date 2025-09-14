# E-Ticaret API

Laravel 9 ve PostgreSQL kullanılarak geliştirilmiş RESTful e-ticaret API'si.

## Özellikler

### 🔐 Kullanıcı Yönetimi
- **POST /api/register** - Kullanıcı kaydı (email, şifre, ad)
- **POST /api/login** - Kullanıcı girişi (JWT token döndürür)
- **GET /api/profile** - Kullanıcı profili görüntüleme
- **PUT /api/profile** - Kullanıcı profili güncelleme
- **POST /api/logout** - Kullanıcı çıkışı (token geçersizleştirme)
- **POST /api/refresh** - JWT token yenileme

### 📂 Kategori Yönetimi (Admin)
- **GET /api/categories** - Tüm kategorileri listele
- **POST /api/categories** - Yeni kategori oluştur
- **PUT /api/categories/{id}** - Kategori güncelle
- **DELETE /api/categories/{id}** - Kategori sil

### 🛍️ Ürün Yönetimi (Admin)
- **GET /api/products** - Ürünleri listele (filtreleme ve sayfalama)
- **GET /api/products/{id}** - Tek ürün detayı
- **POST /api/products** - Yeni ürün ekle
- **PUT /api/products/{id}** - Ürün güncelle
- **DELETE /api/products/{id}** - Ürün sil

### 🛒 Sepet Yönetimi
- **GET /api/cart** - Sepeti görüntüle
- **POST /api/cart/add** - Sepete ürün ekle
- **PUT /api/cart/update** - Sepet ürün miktarı güncelle
- **DELETE /api/cart/remove/{product_id}** - Sepetten ürün çıkar
- **DELETE /api/cart/clear** - Sepeti temizle

### 📦 Sipariş Yönetimi
- **POST /api/orders** - Sipariş oluştur (stok kontrolü ile)
- **GET /api/orders** - Kullanıcının siparişlerini listele
- **GET /api/orders/{id}** - Sipariş detayı

### 🔧 Teknik Özellikler
- **JWT Authentication** - Güvenli token tabanlı kimlik doğrulama
- **Stok Kontrolü** - Sipariş sırasında otomatik stok düşürme
- **Filtreleme ve Sayfalama** - Kategori, fiyat, arama filtreleri
- **Admin Yetkilendirme** - Role-based access control
- **Input Validasyonu** - Form Request sınıfları ile güvenli validasyon
- **SQL Injection Koruması** - Eloquent ORM ile güvenli veritabanı işlemleri
- **Unit Testler** - Kapsamlı API test coverage
- **Docker Support** - Containerized deployment
- **API Logging** - Detaylı API istek/yanıt loglama
- **Swagger Documentation** - Otomatik API dokümantasyonu
- **Database Optimization** - Index'ler ve performans optimizasyonları

## Teknik Gereksinimler

- PHP 8.0+
- PostgreSQL 13+
- Composer
- Laravel 9

## Kurulum

### 1. Projeyi klonlayın
```bash
git clone https://github.com/ferhatgol/Turkticaret-API-Laravel
cd Turkticaret-API-Laravel
```

### 2. Bağımlılıkları yükleyin
```bash
composer install --no-dev
```

### 3. Environment dosyasını oluşturun
```bash
cp .env
```
### 4. Uygulama anahtarını oluşturun
```bash
php artisan key:generate
```

### 5. JWT secret oluşturun
```bash
php artisan jwt:secret
```

### 6. Veritabanı ayarlarını yapın
`.env` dosyasında veritabanı bilgilerini güncelleyin:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=turkticaret
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 7. Veritabanını oluşturun ve migration'ları çalıştırın
```bash
php artisan migrate
```

### 8. Sample data'yı yükleyin
```bash
php artisan db:seed
```

### 9. Uygulamayı başlatın
```bash
php artisan serve
```

API `http://localhost:8000` adresinde çalışacaktır.

## Test Kullanıcıları

### Admin Kullanıcı
- Email: `admin@test.com`
- Şifre: `admin123`

### Normal Kullanıcı
- Email: `user@test.com`
- Şifre: `user123`

## API Endpoints

### Authentication
- `POST /api/register` - Kullanıcı kaydı
- `POST /api/login` - Kullanıcı girişi
- `GET /api/profile` - Kullanıcı profili (Auth gerekli)
- `PUT /api/profile` - Profil güncelleme (Auth gerekli)
- `POST /api/logout` - Çıkış (Auth gerekli)
- `POST /api/refresh` - Token yenileme (Auth gerekli)

### Kategoriler
- `GET /api/categories` - Tüm kategorileri listele
- `POST /api/categories` - Yeni kategori oluştur (Admin)
- `PUT /api/categories/{id}` - Kategori güncelle (Admin)
- `DELETE /api/categories/{id}` - Kategori sil (Admin)

### Ürünler
- `GET /api/products` - Ürünleri listele (filtreleme ve sayfalama)
- `GET /api/products/{id}` - Tek ürün detayı
- `POST /api/products` - Yeni ürün ekle (Admin)
- `PUT /api/products/{id}` - Ürün güncelle (Admin)
- `DELETE /api/products/{id}` - Ürün sil (Admin)

### Sepet
- `GET /api/cart` - Sepeti görüntüle (Auth gerekli)
- `POST /api/cart/add` - Sepete ürün ekle (Auth gerekli)
- `PUT /api/cart/update` - Sepet ürün miktarı güncelle (Auth gerekli)
- `DELETE /api/cart/remove/{product_id}` - Sepetten ürün çıkar (Auth gerekli)
- `DELETE /api/cart/clear` - Sepeti temizle (Auth gerekli)

### Siparişler
- `POST /api/orders` - Sipariş oluştur (Auth gerekli)
- `GET /api/orders` - Kullanıcının siparişlerini listele (Auth gerekli)
- `GET /api/orders/{id}` - Sipariş detayı (Auth gerekli)

## Request/Response Format

Tüm API istekleri ve yanıtları JSON formatındadır.

### Response Format
```json
{
    "success": true|false,
    "message": "İşlem sonucu mesajı",
    "data": {},
    "errors": []
}
```

### HTTP Status Kodları
- `200` - Başarılı işlem
- `201` - Oluşturma başarılı
- `400` - Geçersiz istek
- `401` - Yetkisiz erişim
- `403` - Yetersiz yetki
- `404` - Bulunamadı
- `422` - Validasyon hatası
- `500` - Sunucu hatası

## Authentication

API JWT (JSON Web Token) tabanlı authentication kullanır.

### Token Kullanımı
Header'da Authorization token'ı gönderin:
```
Authorization: Bearer {your_jwt_token}
```

### Login Örneği
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@test.com",
    "password": "user123"
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "Giriş başarılı",
    "data": {
        "user": {
            "id": 2,
            "name": "Test User",
            "email": "user@test.com",
            "role": "user"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    },
    "errors": []
}
```

## Filtreleme ve Sayfalama

### Ürün Listesi Filtreleri
- `page` - Sayfa numarası (varsayılan: 1)
- `limit` - Sayfa başına kayıt sayısı (varsayılan: 20)
- `category_id` - Kategori filtresi
- `min_price` - Minimum fiyat
- `max_price` - Maksimum fiyat
- `search` - Ürün adında arama

### Örnek
```
GET /api/products?category_id=1&min_price=100&max_price=500&search=Elektronik&page=1&limit=10
```

**Response:**
```json
{
    "success": true,
    "message": "Ürünler başarıyla getirildi",
    "data": {
        "products": [
            {
                "id": 1,
                "name": "Elektronik Ürün 1",
                "description": "Elektronik kategorisinde yer alan kaliteli ürün 1",
                "price": "299.99",
                "stock_quantity": 50,
                "category_id": 1,
                "category": {
                    "id": 1,
                    "name": "Elektronik",
                    "description": "Elektronik ürünler ve aksesuarlar"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 10,
            "total": 1,
            "from": 1,
            "to": 1
        }
    },
    "errors": []
}
```

## Validasyon Kuralları

### Kullanıcı Kaydı
- `name`: Zorunlu, minimum 2 karakter
- `email`: Zorunlu, geçerli email, benzersiz
- `password`: Zorunlu, minimum 8 karakter, onaylanmış

### Kullanıcı Girişi
- `email`: Zorunlu, geçerli email
- `password`: Zorunlu

### Profil Güncelleme
- `name`: Zorunlu, minimum 2 karakter
- `email`: Zorunlu, geçerli email, benzersiz (mevcut kullanıcı hariç)

### Kategori Oluşturma/Güncelleme
- `name`: Zorunlu, minimum 3 karakter
- `description`: Opsiyonel

### Ürün Oluşturma/Güncelleme
- `name`: Zorunlu, minimum 3 karakter
- `description`: Opsiyonel
- `price`: Zorunlu, pozitif sayı
- `stock_quantity`: Zorunlu, negatif olmayan tamsayı
- `category_id`: Zorunlu, geçerli kategori ID

### Sepet İşlemleri
- `product_id`: Zorunlu, geçerli ürün ID
- `quantity`: Zorunlu, pozitif tamsayı

## Güvenlik

- **Şifre Güvenliği**: Şifreler bcrypt ile hash'lenir
- **SQL Injection Koruması**: Eloquent ORM kullanılarak
- **XSS Koruması**: Laravel'in built-in XSS koruması
- **Input Validasyonu**: Form Request sınıfları ile
- **Authentication**: JWT token tabanlı
- **Authorization**: Admin middleware ile yetki kontrolü
- **Rate Limiting**: API endpoint'leri için
- **CSRF Koruması**: API için gerekli değil (stateless)

## Sample Data

Proje aşağıdaki sample data ile gelir:
- 2 kullanıcı (1 admin, 1 normal kullanıcı)
- 3 kategori (case study gereksinimi: en az 3 kategori)
- Her kategoride 5 ürün (toplam 15 ürün)

## Postman Collection

Proje ile birlikte gelen `E-Ticaret_API.postman_collection.json` dosyasını Postman'e import ederek tüm endpoint'leri test edebilirsiniz.

## API Test Örnekleri

### Sepete Ürün Ekleme
```bash
curl -X POST http://localhost:8000/api/cart/add \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {your_jwt_token}" \
  -d '{
    "product_id": 1,
    "quantity": 2
  }'
```

### Sipariş Oluşturma
```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {your_jwt_token}" \
  -d '{}'
```

### Admin - Yeni Ürün Ekleme
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {admin_jwt_token}" \
  -d '{
    "name": "Yeni Ürün",
    "description": "Ürün açıklaması",
    "price": 199.99,
    "stock_quantity": 50,
    "category_id": 1
  }'
```

## Docker ile Çalıştırma

### Docker Compose ile Başlatma
```bash
# Tüm servisleri başlat
docker-compose up -d

# Logları görüntüle
docker-compose logs -f

# Servisleri durdur
docker-compose down
```

### Docker Servisleri
- **App**: PHP-FPM uygulama servisi
- **Nginx**: Web server (Port: 8000)
- **PostgreSQL**: Veritabanı (Port: 5432)
- **Redis**: Cache servisi (Port: 6379)
- **phpMyAdmin**: Veritabanı yönetimi (Port: 8080)

### Docker ile Migration ve Seeding
```bash
# Container içinde migration çalıştır
docker-compose exec app php artisan migrate

# Sample data yükle
docker-compose exec app php artisan db:seed

# Fresh migration ve seeding
docker-compose exec app php artisan migrate:fresh --seed
```

## API Dokümantasyonu

### Swagger UI
API dokümantasyonuna erişim:
```
http://localhost:8000/api/documentation
```

### Swagger JSON
```bash
# Swagger JSON dosyasını oluştur
php artisan l5-swagger:generate
```

## Test Sistemi

### Test Çalıştırma
```bash
# Tüm testleri çalıştır
php artisan test

# Belirli test sınıfını çalıştır
php artisan test --filter=AuthTest

# Coverage raporu ile
php artisan test --coverage
```

### Test Kapsamı
- **Authentication Tests** - Kullanıcı kayıt, giriş, profil işlemleri
- **Product Tests** - Ürün CRUD, filtreleme, arama
- **Cart Tests** - Sepet işlemleri, stok kontrolü
- **Order Tests** - Sipariş oluşturma, listeleme, detay görüntüleme

## Logging Sistemi

### Log Dosyaları
- `storage/logs/laravel.log` - Genel uygulama logları
- `storage/logs/api.log` - API istek/yanıt logları
- `storage/logs/error.log` - Hata logları

### Log Seviyeleri
```bash
# Log seviyesini ayarla (.env)
LOG_LEVEL=debug
```

## Geliştirme

### Code Style
```bash
./vendor/bin/pint
```

### Veritabanı Sıfırlama
```bash
php artisan migrate:fresh --seed
```

### Composer Dependencies
```bash
# Production dependencies
composer install --no-dev --optimize-autoloader

# Development dependencies
composer install
```

## Proje Yapısı

```
app/
├── Http/
│   ├── Controllers/Api/          # API Controller'ları
│   ├── Middleware/               # JWT, Admin ve Logging middleware
│   └── Requests/                 # Form Request validasyon sınıfları
├── Models/                       # Eloquent Model'ler
└── Providers/                    # Service Provider'lar

database/
├── factories/                    # Model Factory'leri (Test için)
├── migrations/                   # Veritabanı migration'ları (Index'ler ile)
├── seeders/                      # Veritabanı seeder'ları
└── dump.sql                      # SQL dump dosyası

tests/
├── Feature/                      # Feature testleri
│   ├── AuthTest.php             # Authentication testleri
│   ├── ProductTest.php          # Product testleri
│   ├── CartTest.php             # Cart testleri
│   └── OrderTest.php            # Order testleri
├── Unit/                        # Unit testleri
├── TestCase.php                 # Test base class
└── CreatesApplication.php       # Test application factory

docker/
├── nginx/                       # Nginx konfigürasyonu
└── php/                         # PHP konfigürasyonu

config/
├── l5-swagger.php               # Swagger konfigürasyonu
└── logging.php                  # Logging konfigürasyonu

routes/
└── api.php                      # API route tanımları

Dockerfile                       # Docker container tanımı
docker-compose.yml              # Docker compose konfigürasyonu
phpunit.xml                     # PHPUnit test konfigürasyonu
```

## Case Study Uyumluluğu

Bu proje aşağıdaki case study gereksinimlerini %100 karşılar:

### Temel Gereksinimler
- ✅ **Teknik Gereksinimler**: PHP 8.0+, PostgreSQL 13+, Laravel, JWT
- ✅ **Temel Özellikler**: 5 ana modül (Kullanıcı, Kategori, Ürün, Sepet, Sipariş)
- ✅ **API Gereksinimleri**: RESTful JSON API, HTTP status kodları
- ✅ **Güvenlik**: Bcrypt, SQL injection koruması, JWT authentication
- ✅ **Teslim Gereksinimleri**: Git, README, SQL dump, Postman collection
- ✅ **Sample Data**: 2 kullanıcı, 3 kategori, 15 ürün

### Bonus Özellikler (Ekstra Puan)
- ✅ **Unit Testler**: Kapsamlı API test coverage
- ✅ **Docker Containerization**: Tam containerized deployment
- ✅ **Database Migration Sistemi**: Optimize edilmiş migration'lar
- ✅ **Logging Sistemi**: Detaylı API loglama
- ✅ **API Documentation**: Swagger/OpenAPI dokümantasyonu

### Ek Geliştirmeler
- ✅ **Database Indexes**: Performans optimizasyonu
- ✅ **Model Factories**: Test data generation
- ✅ **API Logging Middleware**: Request/Response logging
- ✅ **Docker Compose**: Multi-service deployment
- ✅ **Swagger Annotations**: Otomatik API docs

## Lisans

MIT License
