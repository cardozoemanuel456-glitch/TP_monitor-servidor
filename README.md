# Monitor de Servidor con Laravel

## Sistema Operativo y Entorno
- **Sistema Operativo:** Windows NT 10.0
- **Versión de PHP:** 8.5.7

---

## Respuestas al Cuestionario

### 1. ¿Por qué la temperatura suele aparecer como "No disponible"?
Porque consultar el hardware (`root\wmi`) en Windows requiere permisos de **Administrador**. Además, muchas placas madre o laptops no comparten esos datos por WMI.

### 2. ¿Por qué el script de PowerShell no debe incluir datos del usuario?
Porque ejecuta comandos directos en la consola (`shell_exec`). Si recibe datos del usuario sin filtrar, alguien podría inyectar y ejecutar comandos maliciosos en la PC.

### 3. ¿Qué riesgo tiene dejar `/api/metrics` sin autenticación?
Cualquiera en internet podría ver información sensible de tu servidor (nombre del equipo, versión de PHP, uso de memoria, CPU y disco).

### 4. Con polling cada 5s, ¿cuántos pedidos por minuto hacen 50 personas?
Cada persona hace 12 pedidos por minuto (60s / 5s).  
50 personas × 12 pedidos = **600 pedidos por minuto**.

### 5. Si una app solo quiere la temperatura, ¿qué recibe de más en `/api/metrics`?
Recibe todo el resto de datos innecesarios: `hostname`, `os`, `php`, `disk`, `memory`, `cpu_pct` y `uptime_s`.