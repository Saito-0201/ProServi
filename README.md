# PROSERVI

**PROSERVI** es una plataforma web desarrollada en **Laravel** que conecta clientes con prestadores de servicios profesionales de manera segura y eficiente.  
Incluye autenticación con **Google OAuth**, verificación de identidad (KYC), gestión de servicios, calificaciones, favoritos, contacto vía **WhatsApp** y un panel administrativo.

---

## 🚀 Características principales
- Autenticación manual y con Google OAuth  
- Gestión de roles y permisos (Spatie Laravel Permission)  
- Verificación KYC para prestadores  
- Calificaciones únicas por cliente/servicio  
- Sistema de favoritos y contacto directo vía WhatsApp  
- Dashboard administrativo completo  
- Búsqueda con filtros por categoría y ubicación  

---

## 🧠 Metodología
El desarrollo sigue la **metodología ágil Scrum**, estructurada en sprints que abarcan:
- HU-01: Iniciar sesión  
- HU-02: Gestión de roles y permisos  
- HU-03 a HU-5: Publicación, búsqueda y calificación de servicios  

---

## 🛠️ Tecnologías utilizadas
- **Backend:** Laravel 10, PHP 8  
- **Frontend:** Bootstrap 5, SweetAlert2  
- **Base de datos:** MySQL  
- **Autenticación:** Laravel Auth, Google OAuth  
- **Control de versiones:** Git & GitHub  

---

## ⚙️ Instalación
1. Clonar el repositorio  
   ```bash
   git clone https://github.com/Saito-0201/ProServi.git
   ```
2. Instalar dependencias  
   ```bash
   composer install
   npm install
   ```
3. Configurar el archivo `.env`  
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Ejecutar migraciones y seeders  
   ```bash
   php artisan migrate --seed
   ```
5. Iniciar el servidor  
   ```bash
   php artisan serve
   ```

---

## 🧩 Arquitectura
El proyecto sigue el patrón **MVC (Modelo-Vista-Controlador)** con módulos independientes para:
- Administración  
- Clientes  
- Prestadores  

---

## 👥 Roles del sistema
- **Administrador:** Control total del sistema y verificación KYC  
- **Prestador:** Publica servicios y gestiona calificaciones  
- **Cliente:** Busca, califica y contacta prestadores  

---

## 📚 Autor
Desarrollado por **Gonzalo Felipez**  
Proyecto académico – Aplicación de Metodología Ágil Scrum  
📧 Contacto: gonzalofelipez0398@gmail.com  
📅 Año: 2025
