# 🩸 Blood Bank Management System

A comprehensive **web-based Blood Bank Management System** built with **Laravel**.  
The system streamlines blood donation, appointment scheduling, inventory management, and blood distribution, providing dedicated portals for **admins, donors, and patients**.

---

## 🌟 Features

### 🔐 User Roles & Authentication
- **Donor Portal**
  - Registration & login
  - Profile management
  - Appointment booking
- **Admin Dashboard**
  - Full system control and monitoring
  - Inventory and user management
- **Patient Management**
  - Patient registration
  - Blood request processing
- **Secure Authentication**
  - Separate authentication flows for donors and admins
  - Session-based access control

---

### ⚙️ Core Functionalities
- **Blood Inventory Management**
  - Track blood units by group and availability
- **Appointment Scheduling**
  - Donors can book, update, and cancel appointments
- **Blood Distribution**
  - Issue blood to patients or hospitals
  - Automatic inventory updates
- **Donor Management**
  - Donor lifecycle tracking
  - Donation history
- **Patient Records**
  - Patient details and blood requirements tracking

---

## 🏗️ Project Structure

### 📂 Controllers Overview

#### 1. **AdminDashboardController**
- Admin dashboard operations
- System statistics & reporting
- User and system configuration

#### 2. **AppointmentController**
- Appointment creation, update, and cancellation
- Scheduling logic
- Appointment status tracking (pending, confirmed, completed)

#### 3. **BloodDistributionController**
- Blood issuance and distribution handling
- Distribution record management
- Inventory updates after distribution

#### 4. **DashboardLoginController**
- Admin authentication
- Session handling
- Security and access control

#### 5. **DonorAuthController**
- Donor registration and login
- Password reset and recovery
- Donor session management

#### 6. **DonorController**
- Donor profile management
- Donation history tracking
- Personal information updates

#### 7. **DonorDashboardController**
- Donor dashboard overview
- Appointment management
- Personal statistics and notifications

#### 8. **PatientController**
- Patient registration and management
- Blood request processing
- Patient request history

---

## 🚀 Installation

### ✅ Prerequisites
- PHP **7.4+**
- Composer
- MySQL
- Laravel **8.x**

---

### 🔧 Setup Steps

#### 1. Clone the repository
```bash
git clone https://github.com/shaik-obydullah/blood-bank.git
cd blood-bank
````

#### 2. Install dependencies

```bash
composer install
```

#### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your database credentials in `.env`:

```env
DB_DATABASE=blood_bank_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### 4. Run migrations and seeders

```bash
php artisan migrate --seed
```

#### 5. Start the development server

```bash
php artisan serve
```

---

## 🗄️ Database Schema

### Key Tables

* `users` – Common authentication table (admins & donors)
* `donors` – Donor information
* `patients` – Patient records
* `blood_inventory` – Blood stock management
* `appointments` – Donation appointments
* `donations` – Donation history
* `blood_distributions` – Blood distribution records

---

## 🔐 Authentication Flow

### Donor Authentication

* Email-based registration
* Login with email & password
* Password reset functionality
* Session-based authentication

### Admin Authentication

* Separate admin login
* Role-based access control
* Admin-only route protection

---

## 💻 Usage

### 👤 For Donors

* Register / login to donor portal
* Complete profile setup
* Book donation appointments
* Track donation history
* Update personal information

### 🧑‍💼 For Administrators

* Login to admin dashboard
* Manage blood inventory
* Process donor appointments
* Handle blood distribution
* Generate reports
* Manage users and system data

---

## 🛠️ Technologies Used

* **Backend:** Laravel 8.x
* **Frontend:** Blade Templates, Bootstrap
* **Database:** MySQL
* **Authentication:** Laravel Breeze / Sanctum
* **Additional Packages:**

  * Laravel Collective (Forms)
  * Intervention Image (Image handling)

---

## 📄 License

This project is open-source and licensed under the **MIT License**.

---

## 👥 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to your branch
5. Open a Pull Request

---

## 🆘 Support

For support, please open an issue in the GitHub repository.

---

## 🔗 Links

* **Repository:** [https://github.com/shaik-obydullah/blood-bank](https://github.com/shaik-obydullah/blood-bank)
* **Issues:** [https://github.com/shaik-obydullah/blood-bank/issues](https://github.com/shaik-obydullah/blood-bank/issues)
* **Pull Requests:** [https://github.com/shaik-obydullah/blood-bank/pulls](https://github.com/shaik-obydullah/blood-bank/pulls)