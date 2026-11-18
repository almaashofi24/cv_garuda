@echo off
title CV. GARUDA RAJA PERKASA DUABELAS - Server Keuangan
cd /d "%~dp0"

set "PATH=C:\Users\USER\Downloads\xampp\php;C:\Users\USER\Downloads\xampp\mysql\bin;%PATH%"

REM Pastikan MySQL berjalan
tasklist /fi "imagename eq mysqld.exe" 2>NUL | find /i "mysqld.exe" >NUL
if "%ERRORLEVEL%"=="1" (
    echo Menjalankan layanan database MySQL...
    start "" /B "C:\Users\USER\Downloads\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\Users\USER\Downloads\xampp\mysql\bin\my.ini" --standalone
    timeout /t 2 /nobreak >NUL
)

echo ===================================================================
echo   SISTEM KEUANGAN - CV. GARUDA RAJA PERKASA DUABELAS
echo   Alamat: Jl. Trans Kalimantan Ruko Keling Kumang No.2, Tayan Hilir
echo ===================================================================
echo.
echo   [OK] Database MySQL telah aktif.
echo   [OK] Menjalankan server aplikasi di port 8000...
echo.
echo   Buka browser Anda di: http://127.0.0.1:8000
echo   (Tekan Ctrl + C di terminal ini untuk berhenti)
echo ===================================================================
echo.

REM Buka browser otomatis
start http://127.0.0.1:8000

"C:\Users\USER\Downloads\xampp\php\php.exe" artisan serve --port=8000
pause

