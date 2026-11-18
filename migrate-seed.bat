@echo off
title Reset & Seed Database - CV. GARUDA
cd /d "%~dp0"

set "PATH=C:\Users\USER\Downloads\xampp\php;C:\Users\USER\Downloads\xampp\mysql\bin;%PATH%"

echo Mereset database dan mengimpor data awal (seeders)...
php artisan migrate:fresh --seed
echo.
echo Selesai!
pause

