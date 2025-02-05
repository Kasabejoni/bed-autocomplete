# 🚀 Lumen (PHP) Backend - Dockerized

This guide will help you set up and run the **Lumen (PHP) backend** using **Docker**.

---

## 📌 Prerequisites
Make sure you have the following installed on your system:
- **Docker** & **Docker Compose** → [Download](https://www.docker.com/get-started)
- **Git** → [Download](https://git-scm.com/downloads)

---

## 🛠️ Setup Instructions

### **1️⃣ Clone the Repository**
```sh
git clone https://github.com/Kasabejoni/bed-autocomplete.git
cd bed-autocomplete/backend
```

### **2️⃣ Create `.env` File**
```sh
cp .env.example .env
```
Make sure `.env` is properly configured with your database settings.

---

## 🐳 Running with Docker

### **🔹 Start the Backend**
Run the following command from the `backend` directory:
```sh
docker-compose up --build -d
```
➡️ **Lumen API will be available at:** `http://localhost:8000`

### **🔹 Check Running Containers**
```sh
docker ps
```

### **🔹 Stop Docker Services**
```sh
docker-compose down
```

---

## ✅ Troubleshooting
### **1️⃣ Missing `.env` File Error?**
Run:
```sh
cp .env.example .env
```

### **2️⃣ Debugging Docker Logs**
```sh
docker logs lumen_api
```

🚀 **Your Lumen backend is now running with Docker!** 🎉
