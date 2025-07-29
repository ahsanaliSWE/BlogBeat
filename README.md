<img width="3140" height="1996" alt="localhost_hidaya_blogbeat_settings php" src="https://github.com/user-attachments/assets/a8f8d379-6a14-4171-b336-85f7c556f21b" /># 📝 BlogBeat - PHP Blogging Platform

BlogBeat is a full-featured blogging system built with PHP and MySQL, designed for content creators and readers alike. It supports dynamic blog creation, user registration, commenting, blog following, category filtering, post attachments, and admin panel management — all wrapped in a clean Bootstrap-based UI.

---

## 📸 Screenshots

### 🔹 ERD (Entity Relationship Diagram)
![blogbeatfinal](https://github.com/user-attachments/assets/c8c8a69b-6910-4363-bc9e-742baef305ca)


### 🔹 Home Page
<img width="3140" height="4216" alt="localhost_hidaya_blogbeat_home php" src="https://github.com/user-attachments/assets/7ead56e8-a871-4bb8-8dfe-ae76a41e2158" />

### 🔹 Blog View
<img width="3140" height="3216" alt="localhost_hidaya_blogbeat_blogs php" src="https://github.com/user-attachments/assets/7891f7aa-15d9-4dce-a0cd-9fc34afc3b91" />
<img width="3140" height="2604" alt="localhost_hidaya_blogbeat_view_blog php_blog_id=21" src="https://github.com/user-attachments/assets/2301abb8-bdf7-4cf5-ad6a-d25ab7a32dbb" />

### 🔹 Post Detail & Comments
<img width="3140" height="3792" alt="localhost_hidaya_blogbeat_view_post php_blog_id=22 post_id=37" src="https://github.com/user-attachments/assets/6d78c6e6-437b-4d4d-8c7c-e888b0c1015f" />

### 🔹 Admin Dashboard
<img width="3140" height="3788" alt="localhost_hidaya_blogbeat_admin_admin_dashboard php_login=success" src="https://github.com/user-attachments/assets/70914f5f-f681-48e7-bd5f-cf613bc413e1" />
![Screenshot 2025-05-29 205347](https://github.com/user-attachments/assets/5c2c66ee-fa3d-458f-addf-55d432f68593)

### 🔹 Changed Blog Theme
Customize how your blog looks! Here’s a preview of an updated theme style.
<img width="3140" height="1996" alt="localhost_hidaya_blogbeat_settings php" src="https://github.com/user-attachments/assets/6cf7c0c6-b095-4889-aea7-962c01348597" />



---

## 🚀 Features

- ✅ User registration and login (admin/user roles)
- 📄 **PDF Generation on Registration using FPDF**  
  When a user registers, a PDF receipt is generated containing their details.
- 🧾 Create & manage blogs
- 📝 Post creation with categories, summaries, descriptions, featured images, and attachments
- 💬 Commenting system with moderation
- 📎 File attachments support (images, PDFs, etc.)
- 📂 Category and blog filtering
- 🔍 Search posts by keyword, author, date, or month
- 👤 User profile editing
- 🔔 Email notifications for:
  - Feedback submissions
  - Account approval/activation
  - New post in followed blogs
- 🔄 Blog following/unfollowing system
- 🎨 **Website theme customization in real-time**
- 📊 Admin panel with blog/user/post/comment management


---

## 🛠️ Tech Stack

- **Backend**: PHP (no framework)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **AJAX**: For smoother user actions (e.g., follow/unfollow)
- **PHPMailer**: For sending emails
- **FPDF**: For dynamic PDF file generation (on registration)
- **Session-based login system**

---

## 📦 Installation

1. **Clone the repository**
```bash
git clone https://github.com/ahsanaliSWE/BlogBeat.git
cd BlogBeat
