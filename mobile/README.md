## ⚙️ Configuración

1. Copiar variables de entorno:
```bash
cp .env.example .env
```

2. Editar `.env` y cambiar la IP:
   - Windows: `ipconfig` → IPv4 Address
   - Mac/Linux: `ifconfig` → inet
   - Ejemplo: `192.168.1.100`

3. Asegurarse que el backend esté corriendo:
```bash
cd ../backend
php artisan serve
```