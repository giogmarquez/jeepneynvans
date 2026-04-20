@echo off
setlocal enabledelayedexpansion

echo ======================================================
echo Starting Palompon Transit Real-time System
echo ======================================================
echo.

echo [INFO] This local script is safe. If Windows SmartScreen marks it as dangerous, choose "Run anyway".

echo [INFO] Checking for zone identifier and unblocking local file metadata...
if exist "%~f0:Zone.Identifier" (
    powershell -NoProfile -Command "try { Unblock-File -Path '%~f0' -ErrorAction Stop; Write-Host '[INFO] Zone identifier removed.' } catch { Write-Host '[WARN] Unblock failed (may require admin):' $_.Exception.Message }"
) else (
    echo [INFO] No zone identifier present.
)

echo [INFO] Detecting PHP installation...
set "PHP_PATH=C:\xampp\php\php.exe"

if not exist "%PHP_PATH%" (
    for /f "delims=" %%p in ('where php 2^>nul') do set "PHP_PATH=%%p"
)

if not exist "%PHP_PATH%" (
    echo [ERROR] PHP executable not found.
    echo Please install XAMPP or set PHP in PATH, then re-run this script.
    pause
    exit /b 1
)

rem Ensure this path is executable and local to prevent unexpected script execution.
if /i "%PHP_PATH:~-4%" neq ".exe" (
    echo [ERROR] PHP_PATH must point to an .exe file. Detected: %PHP_PATH%
    pause
    exit /b 1
)

set "PROJECT_DIR=%~dp0"
rem Use explicit absolute path for spark script
set "SPARK_SCRIPT=%PROJECT_DIR%spark"

if not exist "%SPARK_SCRIPT%" (
    echo [ERROR] Spark launcher not found at %SPARK_SCRIPT%
    echo Ensure this file is in the project root.
    pause
    exit /b 1
)

pushd "%PROJECT_DIR%"

echo.
echo ========== STARTING SERVICES ==========
echo.

REM Start WebSocket Server in new window
echo [INFO] Starting WebSocket Server on port 8081...
start "WebSocket Server" /MIN "%PHP_PATH%" "%SPARK_SCRIPT%" ws:serve

REM Start Web Server
echo [INFO] Starting Web Server on http://localhost:8000...
echo.
echo ========================================
echo Both services are now running:
echo - Web Server:      http://localhost:8000
echo - WebSocket:       ws://localhost:8081
echo - Broadcast Port:  localhost:8082
echo ========================================
echo.
echo [INFO] Press CTRL+C to stop all services.
echo.

"%PHP_PATH%" "%SPARK_SCRIPT%" serve

REM Cleanup on exit
echo.
echo [INFO] Stopping all services...
taskkill /f /im php.exe 2>nul
popd
exit
