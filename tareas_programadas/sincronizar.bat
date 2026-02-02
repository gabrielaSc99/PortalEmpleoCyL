@echo off
REM Sincronizacion automatica de ofertas de empleo
REM Ejecutar con el Programador de tareas de Windows

REM Ruta de PHP de XAMPP (ajustar si es diferente)
set PHP_PATH=C:\Users\34642\Desktop\xampp\php\php.exe
set SCRIPT_PATH=C:\Users\34642\Desktop\xampp\htdocs\PortalEmpleoCyL\tareas_programadas\sincronizar_ofertas.php

REM Verificar que PHP existe
if not exist "%PHP_PATH%" (
    echo ERROR: No se encuentra PHP en %PHP_PATH%
    echo Ajusta la variable PHP_PATH en este archivo
    pause
    exit /b 1
)

echo [%date% %time%] Iniciando sincronizacion...
"%PHP_PATH%" "%SCRIPT_PATH%"
echo [%date% %time%] Finalizado.
