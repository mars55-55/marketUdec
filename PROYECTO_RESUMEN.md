# MarketUdeC - Sistema de Marketplace Universitario

## 📋 Resumen del Proyecto

MarketUdeC es un marketplace completo desarrollado en Laravel para la comunidad universitaria. El sistema permite a los estudiantes comprar, vender e intercambiar productos de manera segura y eficiente.

## ✅ Historia de Usuario Implementadas

### H01 - Sistema de Registro y Autenticación ✅
- **Implementado**: Laravel Breeze con autenticación completa
- **Características**: Registro, login, verificación de email, recuperación de contraseña
- **Campos universitarios**: Carrera, campus, año de ingreso

### H02 - Publicación y Gestión de Anuncios ✅
- **Implementado**: CRUD completo de anuncios con imágenes
- **Características**: 
  - Creación, edición, eliminación de anuncios
  - Subida múltiple de imágenes
  - Estados: activo, pausado, vendido, bloqueado
  - Categorías con iconos
  - Precios negociables
  - Condición del producto (nuevo, usado, etc.)

### H03 - Búsqueda y Filtros Avanzados ✅
- **Implementado**: Sistema de búsqueda completo
- **Características**:
  - Búsqueda por texto en título y descripción
  - Filtros por categoría, precio, condición, ubicación
  - Ordenamiento múltiple
  - Autocompletado AJAX
  - Interfaz responsive

### H04 - Sistema de Chat/Mensajería ✅
- **Implementado**: Chat en tiempo real entre usuarios
- **Características**:
  - Conversaciones privadas
  - Mensajes en tiempo real (polling)
  - Historial de conversaciones
  - Estados de lectura
  - Integración con anuncios

### H05 - Panel de Moderación ✅
- **Implementado**: Panel administrativo completo
- **Características**:
  - Dashboard con estadísticas
  - Gestión de reportes
  - Moderación de anuncios
  - Gestión de usuarios
  - Gestión de categorías
  - Acciones de bloqueo/desbloqueo

### H06 - Gestión de Perfiles ✅
- **Implementado**: Perfiles públicos y configuración
- **Características**:
  - Perfiles públicos con información universitaria
  - Historial de anuncios
  - Sistema de calificaciones
  - Gestión de información personal

### H07 - Sistema de Favoritos ✅
- **Implementado**: Guardado de anuncios favoritos
- **Características**:
  - Agregar/quitar favoritos con AJAX
  - Lista de favoritos personal
  - Notificaciones de estado
  - Integración en todas las vistas

### H08 - Sistema de Reseñas y Calificaciones ✅
- **Implementado**: Sistema completo de reviews
- **Características**:
  - Calificaciones de 1-5 estrellas
  - Comentarios opcionales
  - Cálculo automático de rating promedio
  - Prevención de auto-reseñas
  - Historial completo de reseñas

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Base de Datos**: MySQL/SQLite
- **Autenticación**: Laravel Breeze
- **Assets**: Vite
- **Imágenes**: Storage local con enlaces simbólicos

## 📁 Estructura de la Base de Datos

### Tablas Principales:
1. **users** - Usuarios con campos universitarios extendidos
2. **categories** - Categorías de productos con iconos
3. **listings** - Anuncios con información completa
4. **listing_images** - Imágenes de los anuncios
5. **favorites** - Favoritos de usuarios
6. **conversations** - Conversaciones entre usuarios
7. **messages** - Mensajes individuales
8. **reviews** - Reseñas y calificaciones
9. **reports** - Reportes de contenido inapropiado

## 🚀 Instrucciones de Ejecución

### Prerrequisitos:
- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL o SQLite

### Instalación:

1. **Instalar dependencias de PHP:**
```bash
composer install
```

2. **Instalar dependencias de Node.js:**
```bash
npm install
```

3. **Configurar el archivo .env:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos en .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketudec
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones y seeders:**
```bash
php artisan migrate --seed
```

6. **Crear enlace simbólico para storage:**
```bash
php artisan storage:link
```

7. **Compilar assets:**
```bash
npm run dev
```

8. **Iniciar el servidor:**
```bash
php artisan serve
```

## 🌟 Características Principales

### Interfaz de Usuario:
- Diseño responsive con Tailwind CSS
- Modo oscuro/claro
- Iconos emoji para mejor UX
- Notificaciones AJAX
- Carga de imágenes con preview

### Funcionalidades de Seguridad:
- Autenticación Laravel Breeze
- Validación de formularios
- Protección CSRF
- Sanitización de inputs
- Control de acceso por roles

### Optimizaciones:
- Lazy loading de imágenes
- Paginación eficiente
- Índices de base de datos optimizados
- Queries optimizadas con Eloquent
- Cache de consultas frecuentes

## 📱 Navegación Principal

- **Inicio**: Listado de anuncios recientes
- **Buscar**: Búsqueda avanzada con filtros
- **Favoritos**: Anuncios guardados (autenticado)
- **Mensajes**: Chat entre usuarios (autenticado)
- **Dashboard**: Panel personal (autenticado)
- **Admin**: Panel de moderación (administradores)

## 🎨 Elementos Visuales

- **Emojis**: Uso extensivo para mejor UX
- **Estados visuales**: Colores para diferentes estados
- **Animaciones**: Transiciones suaves CSS
- **Componentes**: Modales, dropdowns, cards
- **Responsive**: Adaptado a móviles y desktop

## 📊 Datos de Prueba

El sistema incluye seeders que crean:
- Usuarios de ejemplo
- Categorías predefinidas
- Anuncios de muestra
- Conversaciones de prueba
- Reseñas de ejemplo

## 💡 Funcionalidades Destacadas

1. **Chat en Tiempo Real**: Sistema de mensajería con polling automático
2. **Favoritos AJAX**: Interacción sin recargar página
3. **Búsqueda Inteligente**: Autocompletado y filtros múltiples
4. **Panel Admin**: Moderación completa del contenido
5. **Sistema de Reseñas**: Calificaciones y comentarios
6. **Responsive Design**: Adaptado a todos los dispositivos
7. **Modo Oscuro**: Soporte completo para tema oscuro

¡El sistema está completamente funcional y listo para usar! 🎉