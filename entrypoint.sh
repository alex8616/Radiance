#!/bin/bash

# Esperar a que la base de datos esté lista (útil en despliegues cloud)
sleep 3

# Ejecutar migraciones
# NOTA: Usa 'migrate:fresh --seed' solo la primera vez. 
# Luego cámbialo a 'migrate --force' para no borrar tus datos en cada reinicio.
php artisan migrate:fresh --seed --force

# Iniciar el proceso de Apache en primer plano
exec apache2-foreground