@echo off
chcp 65001 >nul
cd /d "%~dp0"
echo ========================================
echo   TIR TAKIP - Sunucu Baslatiliyor
echo ========================================
echo.
echo Tarayicida acin: http://localhost:8001
echo.
echo Bu pencereyi KAPATMAYIN!
echo ========================================
php artisan serve --port=8001
echo.
echo Sunucu durdu.
pause