# 🛒 Market Shop - Elektron Tijorat Web Sayti

## 📌 Loyiha haqida

**Market Shop** bu PHP va MySQL yordamida yaratilgan elektron tijorat (E-Commerce) web sayti hisoblanadi. Tizim foydalanuvchilarga mahsulotlarni ko‘rish, savatchaga qo‘shish va buyurtma berish imkonini beradi. Administrator esa mahsulotlar, foydalanuvchilar va buyurtmalarni boshqarishi mumkin.

---

# ✨ Asosiy imkoniyatlar

## 👤 Foydalanuvchi qismi

* Ro‘yxatdan o‘tish
* Tizimga kirish
* Profilni tahrirlash
* Parolni o‘zgartirish
* Mahsulotlarni ko‘rish
* Kategoriyalar bo‘yicha saralash
* Savatchaga mahsulot qo‘shish
* Buyurtma berish
* Buyurtmalar tarixini ko‘rish
* Buyurtma holatini kuzatish

### 📦 Buyurtma statuslari

```text
Ko‘rib chiqilyapdi
↓
Tasdiqlandi
↓
Yetkazilyapdi
↓
Yetkazildi
```

yoki

```text
Ko‘rib chiqilyapdi
↓
Bekor qilindi
```

---

## ⚙️ Administrator paneli

* Dashboard statistikasi
* Mahsulot qo‘shish
* Mahsulotni tahrirlash
* Mahsulotni o‘chirish
* Rasm yuklash
* Foydalanuvchilarni boshqarish
* Buyurtmalarni boshqarish
* Buyurtmalarni status bo‘yicha filtrlash
* Buyurtmalarni ketma-ket tasdiqlash
* Yetkazilgan buyurtmalarni ko‘rish

### 📊 Dashboard statistikasi

* Foydalanuvchilar soni
* Mahsulotlar soni
* Buyurtmalar soni

---

# 🤖 Telegram integratsiyasi

Yangi buyurtma berilganda Telegram bot orqali:

* Foydalanuvchi ismi
* Telefon raqami
* Manzil
* Mahsulotlar ro‘yxati
* Jami summa
* Kunlik statistika

administratorga yuboriladi.

---

# 🧱 Texnologiyalar

* PHP
* MySQL
* Bootstrap 5
* HTML5
* CSS3
* JavaScript
* Telegram Bot API

---

# 📂 Loyiha strukturasi

```text
market/
│
├── accounts/
├── admin/
├── assets/
├── bot/
├── cart/
├── config/
├── includes/
├── orders/
├── products/
├── uploads/
│
└── index.php
```

---

# 🗄️ Database jadvallari

* users
* categories
* products
* cart
* orders
* order_items

---

# 🚀 O‘rnatish

## 1. Loyihani clone qilish

```bash
git clone https://github.com/sanatbekcoder/online_market.git
```

---

## 2. Database yaratish

phpMyAdmin orqali yangi database yarating:

```sql
CREATE DATABASE market;
```

---

## 3. SQL faylni import qilish

Berilgan `.sql` faylni import qiling.

---

## 4. Database ulanishini sozlash

`config/database.php`

```php
<?php

$conn = new PDO(
    "mysql:host=localhost;dbname=market",
    "root",
    ""
);
```

---

## 5. Telegram bot sozlash

`bot/send.php`

ichida:

```php
$token="BOT_TOKEN";
$chat_id="CHAT_ID";
```

qiymatlarini almashtiring.

---

# 👑 Admin login

```text
Email: admin@gmail.com
Parol: admin123
```

---

# 📸 Demo imkoniyatlari

* 50 ta test foydalanuvchi
* 10 ta mahsulot
* Test buyurtmalar
* Test savatchalar

---

# 🔒 Xavfsizlik

* password_hash()
* password_verify()
* PDO prepared statements
* Session himoyasi
* Admin role tekshiruvi
* XSS himoyasi

---

# 📌 Muallif

**Sanatbek**

GitHub: https://github.com/sanatbekcoder

---

# ⭐ Eslatma

Loyiha o‘quv va amaliy maqsadlarda yaratilgan.
