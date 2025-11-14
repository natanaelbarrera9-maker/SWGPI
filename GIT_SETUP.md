# 📚 SWGPI - Configuración Git completada

## ✅ Status Actual
- ✓ Repositorio Git inicializado
- ✓ Remote de GitHub configurado
- ✓ Usuario: Natanael
- ✓ Email: natanaelbarrera9@gmail.com
- ✓ Rama principal: master

## 🚀 Cómo usar

### Opción 1: Usando el script automatizado (RECOMENDADO)
```powershell
# Ir a la carpeta del proyecto
cd "c:\wamp64\www\MySchool\SWGPI"

# Ejecutar el script con un mensaje
.\deploy.ps1 "Mi mensaje del commit"
```

**Ejemplos:**
```powershell
.\deploy.ps1 "Agregué nuevo feature de login"
.\deploy.ps1 "Corregí bug en la página de admin"
.\deploy.ps1 "Actualicé estilos CSS"
```

### Opción 2: Comandos manuales
```powershell
# 1. Ver cambios
git status

# 2. Agregar cambios
git add .

# 3. Crear commit
git commit -m "Tu mensaje aquí"

# 4. Subir a GitHub
git push origin master
```

## 📋 Notas importantes

1. **Primera vez**: GitHub te pedirá autenticación. Usa tu usuario y contraseña (o token si tienes 2FA)
2. **.gitignore**: Archivos que NO se suben (logs, config sensible, etc.)
3. **Sin cambios**: El script no falla si no hay cambios, solo lo indica

## 🔗 URLs importantes
- GitHub: https://github.com/natanaelbarrera9-maker/SWGPI
- Proyecto local: C:\wamp64\www\MySchool\SWGPI

---
**¡Listo para trabajar! Solo llámame cuando necesites hacer una carga.** 🎉
