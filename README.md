# 🎓 EduMorph – Adaptive Learning Management System

## 📌 Overview
EduMorph is a **full-stack Adaptive Learning Management Web System** developed during the **Antratech 2.0 Hackathon (18 hours)**.

The system provides **personalized learning experiences** by dynamically adjusting quizzes, content, and recommendations based on user performance.

It integrates frontend, backend APIs, and database to simulate a real-world **EdTech platform**.

---

## 🎯 Problem Statement
Traditional learning systems follow a **one-size-fits-all approach**, which:
- Fails to adapt to individual learning pace  
- Reduces student engagement  
- Lacks personalized feedback  

---

## 💡 Solution
EduMorph solves this by:
- Providing **adaptive quizzes**
- Tracking **user performance**
- Generating **personalized learning paths**
- Displaying insights via **interactive dashboards**

---

## 🧠 Core Features

- 🎯 Adaptive Quiz System  
- 📊 Performance Analytics Dashboard  
- 🧠 Flashcard Learning System  
- 🏆 Leaderboard & Ranking  
- 📅 Daily Activity Tracking  
- 🎮 Gamification (Spin, Fun Activities)  
- 🔐 Authentication System (Login/Register)  

---

## 🏗️ System Architecture

### 🔹 Frontend
- HTML, CSS, JavaScript  
- Multiple modules (Dashboard, Quiz, Flashcards, Leaderboard)

### 🔹 Backend (API)
- PHP APIs:
  - login.php  
  - register.php  
  - save_result.php  
  - get_leaderboard.php  

### 🔹 Database
- MySQL  
- Stores:
  - User data  
  - Quiz results  
  - Scores & leaderboard  

---

## 📂 Project Structure

```bash
EduMorph/
│
├── api/
│   ├── config.php
│   ├── login.php
│   ├── register.php
│   ├── save_result.php
│   ├── get_leaderboard.php
│
├── frontend/
│   ├── index.html
│   ├── dashboard.html
│   ├── adaptive_quiz.html
│   ├── challenges.html
│   ├── flashcard.html
│   ├── leaderboard.html
│   ├── profile.html
│   ├── daily_activity.html
│   ├── fun_activity.html
│   ├── spin.html
│   ├── login.html
│   ├── depart.html
│   ├── diploma_branch.html
│   ├── mba_branch.html
│   ├── marketing_mcq.html
│   ├── com_mcq.html
│
├── database.sql
└── README.md
## ⚙️ Workflow (System Flow)

1. 👤 User Registration / Login
2. 📊 Initial Assessment Test
3. 🧠 Adaptive Engine processes user performance
4. 📚 Personalized quizzes & content generated
5. 📈 Dashboard displays analytics and progress
6. 🔁 Continuous learning and improvement loop

---

## ⚙️ How to Run

### 1️⃣ Setup Database

* Import `database.sql` into MySQL

### 2️⃣ Configure Backend

* Open:

```bash
api/config.php
```

* Update database credentials:

  * Host
  * Username
  * Password
  * Database Name

### 3️⃣ Run Project

* Start XAMPP / WAMP server
* Open in browser:

```bash
http://localhost/EduMorph/frontend/index.html
```

---

## 📊 Outputs

* 📈 User Dashboard with performance tracking
* 🧠 Adaptive quizzes based on user level
* 🏆 Leaderboard ranking system
* 📅 Daily activity & engagement tracking

---

## 📈 Key Insights

* Adaptive learning improves engagement and retention
* Personalized quizzes enhance performance
* Gamification increases student motivation

---

## ⚠️ Challenges Faced

* Integrating frontend with PHP APIs
* Managing database and user sessions
* Implementing adaptive logic within limited time (18 hours)

---

## 🔮 Future Improvements

* 🤖 AI/ML-based recommendation engine
* ☁️ Cloud deployment (Firebase / AWS)
* 📱 Mobile application version
* 🧑‍🏫 Admin & Teacher dashboard
* 📊 Advanced analytics & reports

---

## 🏆 Hackathon Details

* **Event:** Antratech 2.0 Hackathon
* **Location:** R. C. Patel College of Engineering and Polytechnic, Shirpur
* **Duration:** 18 Hours
* **Team:** RPDKNexacreator’s

---

## 👨‍💻 Author

**Rohit Devidas Koli**
📧 Email: [Rohitkoli97p@gmail.com](mailto:Rohitkoli97p@gmail.com)
🔗 GitHub: https://github.com/Rohitkoli1096

---

## 📜 License

MIT License

---

## ⭐ Acknowledgement

Thanks to **Antratech 2.0 Hackathon** organizers for providing this opportunity.
