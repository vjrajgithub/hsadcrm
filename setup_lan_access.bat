@echo off
setlocal ENABLEDELAYEDEXPANSION

REM =====================================
REM HSAD CRM - One-click LAN Server
REM =====================================

set PROJECT_PATH=D:\wamp64\www\crm
set PORT=8000

cd /d %PROJECT_PATH%

REM Detect local IPv4 address dynamically
for /f "tokens=14 delims= " %%a in ('ipconfig ^| findstr "IPv4"') do set LAN_IP=%%a

REM Remove trailing colon if present
set LAN_IP=%LAN_IP::=%

echo.
echo ==============================
echo Starting HSAD CRM Server
echo ==============================
echo Project Path : %PROJECT_PATH%
echo Localhost    : http://localhost:%PORT%/
echo LAN Access   : http://%LAN_IP%:%PORT%/
echo ==============================
echo.
echo Press Ctrl+C to stop the server
echo.

REM Auto-open browser with LAN URL
start http://%LAN_IP%:%PORT%/

REM Run PHP built-in server on all interfaces (so LAN devices can connect)
php -S 0.0.0.0:%PORT% -t .

pause
