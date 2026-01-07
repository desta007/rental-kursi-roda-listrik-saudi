# MobilityKSA - Dokumentasi Aplikasi Rental Kursi Roda Listrik

## 📋 Ringkasan Proyek

**MobilityKSA** adalah aplikasi rental kursi roda listrik yang dirancang khusus untuk jamaah haji dan umrah di Arab Saudi. Aplikasi ini menyediakan layanan penyewaan kursi roda listrik dengan berbagai tipe dan fitur, mendukung pickup di stasiun atau pengiriman langsung ke lokasi pengguna.

---

## 🏗️ Arsitektur Sistem

```mermaid
graph TB
    subgraph "Frontend"
        A[Mobile App - HTML/CSS/JS Prototype]
        B[Admin Panel - HTML/CSS/JS Prototype]
    end
    
    subgraph "Backend - Laravel"
        C[API Routes - /api/v1]
        D[Admin Routes - /admin]
        E[Controllers]
        F[Models]
        G[Database - SQLite]
    end
    
    A --> C
    B --> D
    C --> E
    D --> E
    E --> F
    F --> G
```

---

## 📁 Struktur Direktori

```
rental-kursi-roda-listrik-saudi/
├── backend/                    # Laravel Backend
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── Admin/         # Controller untuk admin panel
│   │   │   └── Api/           # Controller untuk mobile API
│   │   └── Models/            # Eloquent Models
│   ├── database/
│   │   ├── migrations/        # Database migrations
│   │   └── seeders/           # Sample data seeders
│   ├── routes/
│   │   ├── api.php            # API routes untuk mobile
│   │   └── admin.php          # Routes untuk admin panel
│   └── resources/             # Views dan assets
│
└── prototype/                  # UI Prototypes (HTML/CSS/JS)
    ├── mobile/                 # Mobile app prototype
    │   ├── home.html
    │   ├── login.html
    │   ├── register.html
    │   ├── wheelchairs.html
    │   ├── wheelchair-detail.html
    │   ├── booking.html
    │   ├── payment.html
    │   ├── bookings.html
    │   └── profile.html
    └── admin/                  # Admin panel prototype
        ├── login.html
        ├── dashboard.html
        ├── wheelchairs.html
        ├── bookings.html
        └── users.html
```

---

## 📊 Model Database (Entity Relationship)

```mermaid
erDiagram
    User ||--o{ Booking : "has many"
    Wheelchair ||--o{ Booking : "has many"
    Station ||--o{ Wheelchair : "has many"
    Station ||--o{ Booking : "has many"
    WheelchairType ||--o{ Wheelchair : "has many"
    Booking ||--o{ Payment : "has many"
    
    User {
        int id PK
        string name
        string phone
        string email
        string identity_type
        string identity_number
        string identity_photo
        string language
        string status
        string verification_status
        string otp_code
        datetime otp_expires_at
    }
    
    Station {
        int id PK
        string name
        string name_ar
        string city
        string address
        string address_ar
        decimal latitude
        decimal longitude
        string operating_hours
        string contact_phone
        string image
        boolean is_active
    }
    
    WheelchairType {
        int id PK
        string name
        string name_ar
        text description
        text description_ar
        string image
        decimal daily_rate
        decimal weekly_rate
        decimal monthly_rate
        decimal deposit_amount
        int battery_range_km
        int max_weight_kg
        int max_speed_kmh
        json features
        json specifications
        boolean is_active
    }
    
    Wheelchair {
        int id PK
        string code
        int wheelchair_type_id FK
        int station_id FK
        string brand
        string model
        string battery_capacity
        string status
        date last_maintenance
        date next_maintenance
        json photos
        text notes
    }
    
    Booking {
        int id PK
        string booking_code
        int user_id FK
        int wheelchair_id FK
        int station_id FK
        datetime start_date
        datetime end_date
        string pickup_type
        string delivery_address
        decimal delivery_latitude
        decimal delivery_longitude
        string status
        decimal rental_amount
        decimal delivery_fee
        decimal discount_amount
        decimal vat_amount
        decimal deposit_amount
        decimal total_amount
        string promo_code
        json addons
        text notes
        text cancellation_reason
        datetime picked_up_at
        datetime returned_at
        datetime admin_read_at
    }
    
    Payment {
        int id PK
        int booking_id FK
        string type
        string payment_method
        decimal amount
        string currency
        string status
        string stripe_payment_intent_id
        string stripe_charge_id
        json gateway_response
        string receipt_url
        datetime paid_at
    }

    Setting {
        int id PK
        string key
        text value
        string type
    }
```

---

## 🔄 Alur Aplikasi

### 1. Alur Autentikasi (OTP-based)

```mermaid
sequenceDiagram
    participant U as User
    participant A as Mobile App
    participant API as Backend API
    
    U->>A: Masukkan nomor telepon
    A->>API: POST /api/v1/auth/request-otp
    API-->>A: OTP dikirim via SMS
    U->>A: Masukkan kode OTP
    A->>API: POST /api/v1/auth/verify-otp
    API-->>A: Token autentikasi (Sanctum)
    
    Note over U,API: Jika pengguna baru
    U->>A: Lengkapi profil
    A->>API: POST /api/v1/auth/register
    API-->>A: User terdaftar
    
    U->>A: Upload identitas (KTP/Passport)
    A->>API: POST /api/v1/profile/upload-identity
    API-->>A: Identitas tersimpan
```

### 2. Alur Pencarian & Pemilihan Kursi Roda

```mermaid
flowchart TD
    A[Home Page] --> B[Browse Wheelchairs]
    B --> C{Filter & Search}
    C --> D[Tampilkan Daftar Kursi Roda]
    D --> E[Pilih Kursi Roda]
    E --> F[Lihat Detail & Spesifikasi]
    F --> G{Cek Ketersediaan}
    G -->|Tersedia| H[Lanjut ke Booking]
    G -->|Tidak Tersedia| I[Pilih Tanggal Lain / Kursi Lain]
    I --> C
```

### 3. Alur Pemesanan (Booking)

```mermaid
sequenceDiagram
    participant U as User
    participant A as Mobile App
    participant API as Backend API
    
    U->>A: Pilih kursi roda
    A->>API: GET /api/v1/wheelchairs/{id}/availability
    API-->>A: Status ketersediaan
    
    U->>A: Pilih tanggal rental
    U->>A: Pilih metode pickup (Self/Delivery)
    U->>A: Pilih add-ons (opsional)
    U->>A: Masukkan kode promo (opsional)
    
    A->>A: Hitung total harga
    Note over A: Rental + VAT 15% + Delivery Fee + Deposit
    
    U->>A: Setujui Terms & Conditions
    A->>API: POST /api/v1/bookings
    API-->>A: Booking created (status: pending)
    A->>U: Lanjut ke pembayaran
```

**Detail Perhitungan Harga:**
- **Daily Rate**: Tarif harian per tipe kursi roda
- **Weekly Rate**: Diskon untuk sewa 7+ hari
- **Monthly Rate**: Diskon untuk sewa 30+ hari
- **VAT**: 15% pajak
- **Deposit**: Deposit yang dikembalikan setelah pengembalian
- **Delivery Fee**: SAR 30 (jika pilih delivery)

### 4. Alur Pembayaran (Payment)

```mermaid
sequenceDiagram
    participant U as User
    participant A as Mobile App
    participant API as Backend API
    participant S as Stripe
    
    U->>A: Lanjut ke pembayaran
    A->>API: POST /api/v1/payments/initiate
    API->>S: Create Payment Intent
    S-->>API: Payment Intent ID + Client Secret
    API-->>A: Client secret untuk pembayaran
    
    U->>A: Masukkan detail kartu
    A->>S: Confirm payment (Stripe SDK)
    S-->>A: Payment successful
    
    A->>API: POST /api/v1/payments/confirm
    API-->>A: Payment confirmed
    
    Note over API: Update booking status: "confirmed"
    Note over API: Kirim notifikasi ke admin
    
    A->>U: Tampilkan konfirmasi booking
```

**Metode Pembayaran yang Didukung:**
- Kartu Kredit/Debit (via Stripe)

### 5. Alur Status Booking

```mermaid
stateDiagram-v2
    [*] --> pending: Booking dibuat
    pending --> confirmed: Pembayaran berhasil
    pending --> cancelled: User membatalkan
    confirmed --> active: Kursi roda diambil
    confirmed --> cancelled: User membatalkan
    active --> completed: Kursi roda dikembalikan
    completed --> [*]
    cancelled --> [*]
```

**Status Booking:**
| Status | Deskripsi |
|--------|-----------|
| `pending` | Booking dibuat, menunggu pembayaran |
| `confirmed` | Pembayaran berhasil, menunggu pickup |
| `active` | Kursi roda sedang digunakan |
| `completed` | Kursi roda sudah dikembalikan |
| `cancelled` | Booking dibatalkan |

### 6. Alur Pengembalian

```mermaid
flowchart TD
    A[User ke Stasiun] --> B[Serahkan Kursi Roda]
    B --> C[Staff Cek Kondisi]
    C --> D{Kondisi OK?}
    D -->|Ya| E[Update Status: Completed]
    D -->|Tidak| F[Dokumentasi Kerusakan]
    F --> G[Potong dari Deposit]
    G --> E
    E --> H[Proses Refund Deposit]
    H --> I[Kirim Notifikasi ke User]
```

---

## 🔌 API Endpoints

### Public Endpoints (Tanpa Autentikasi)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/v1/auth/request-otp` | Request OTP untuk login |
| POST | `/api/v1/auth/verify-otp` | Verifikasi OTP |
| GET | `/api/v1/wheelchair-types` | Daftar tipe kursi roda |
| GET | `/api/v1/wheelchairs` | Daftar kursi roda |
| GET | `/api/v1/wheelchairs/{id}` | Detail kursi roda |
| GET | `/api/v1/wheelchairs/{id}/availability` | Cek ketersediaan |
| GET | `/api/v1/stations` | Daftar stasiun pickup |
| GET | `/api/v1/stations/{id}` | Detail stasiun |
| POST | `/api/v1/payments/webhook` | Stripe webhook |

### Protected Endpoints (Memerlukan Autentikasi)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/v1/auth/register` | Registrasi/update profil |
| GET | `/api/v1/auth/me` | Data user saat ini |
| POST | `/api/v1/auth/logout` | Logout |
| PUT | `/api/v1/profile` | Update profil |
| POST | `/api/v1/profile/upload-identity` | Upload foto identitas |
| GET | `/api/v1/bookings` | Daftar booking user |
| POST | `/api/v1/bookings` | Buat booking baru |
| GET | `/api/v1/bookings/{id}` | Detail booking |
| POST | `/api/v1/bookings/{id}/cancel` | Batalkan booking |
| POST | `/api/v1/payments/initiate` | Mulai pembayaran |
| POST | `/api/v1/payments/confirm` | Konfirmasi pembayaran |
| GET | `/api/v1/payments/{id}` | Status pembayaran |

---

## 🖥️ Admin Panel

### Fitur Admin

```mermaid
flowchart LR
    A[Admin Login] --> B[Dashboard]
    B --> C[Kelola Stasiun]
    B --> D[Kelola Tipe Kursi Roda]
    B --> E[Kelola Kursi Roda]
    B --> F[Kelola Booking]
    B --> G[Kelola User]
    B --> H[Pengaturan]
    B --> I[Global Search]
```

### Admin Routes

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET/POST | `/admin/login` | Login admin |
| POST | `/admin/logout` | Logout admin |
| GET | `/admin` | Dashboard |
| CRUD | `/admin/stations` | Kelola stasiun |
| CRUD | `/admin/wheelchair-types` | Kelola tipe kursi roda |
| CRUD | `/admin/wheelchairs` | Kelola kursi roda |
| CRUD | `/admin/bookings` | Kelola booking |
| CRUD | `/admin/users` | Kelola user |
| GET/PUT | `/admin/settings` | Pengaturan aplikasi |
| GET | `/admin/search` | Pencarian global |

---

## 📱 Halaman Mobile App

| Halaman | File | Fungsi |
|---------|------|--------|
| Home | `home.html` | Dashboard utama, promo, kursi roda unggulan |
| Login | `login.html` | Input nomor telepon untuk OTP |
| Register | `register.html` | Form registrasi profil lengkap |
| Browse | `wheelchairs.html` | Daftar kursi roda dengan filter |
| Detail | `wheelchair-detail.html` | Detail spesifikasi kursi roda |
| Booking | `booking.html` | Form pemesanan kursi roda |
| Payment | `payment.html` | Halaman pembayaran |
| My Bookings | `bookings.html` | Daftar booking user |
| Profile | `profile.html` | Profil dan pengaturan user |

---

## 🔧 Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 11
- **Database**: SQLite (development)
- **Authentication**: Laravel Sanctum (API tokens)
- **Payment Gateway**: Stripe
- **ORM**: Eloquent

### Frontend (Prototype)
- **HTML5/CSS3**: Struktur dan styling
- **JavaScript**: Interaktivitas
- **Font Awesome**: Icons
- **Google Fonts**: Inter & Noto Sans Arabic

### Fitur Khusus
- **Bilingual Support**: English & Arabic (RTL support)
- **OTP Authentication**: Verifikasi nomor telepon
- **Location-based**: Stasiun terdekat berdasarkan lokasi
- **Dynamic Pricing**: Diskon mingguan/bulanan otomatis

---

## 📍 Lokasi Stasiun (Sample Data)

Aplikasi ini beroperasi di **10 stasiun** di Makkah, Saudi Arabia:

1. **Masjid Al-Haram** - Gate 79
2. **Hilton Makkah** - Ibrahim Al Khalil Rd
3. **King Faisal Hospital** - Al Aziziyah
4. Dan lokasi lainnya di area Makkah

---

## 💰 Model Bisnis

1. **Rental Kursi Roda**: Pendapatan utama dari sewa kursi roda
2. **Add-ons**: Baterai ekstra, cushion premium, sun shade
3. **Delivery Fee**: Biaya tambahan untuk pengantaran
4. **Deposit**: Jaminan keamanan (dikembalikan setelah pengembalian)

---

## 🚀 Cara Menjalankan

### Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Prototype (Static HTML)

Buka file HTML di browser atau jalankan server lokal:

```bash
cd prototype
npx serve .
```

---

## 📝 Catatan Pengembangan

- Prototype menggunakan static HTML untuk demonstrasi UI/UX
- Backend sudah siap dengan API lengkap
- Integrasi Stripe memerlukan konfigurasi API keys di `.env`
- Database SQLite digunakan untuk development, bisa diganti dengan MySQL/PostgreSQL untuk production
