@echo off
setlocal enabledelayedexpansion

rem ============================================================================
rem  check-upload-folders.bat
rem
rem  Makes sure every folder the app's upload code writes to - upload_path in
rem  the CI Upload library config, across My_control.php, Cashier.php,
rem  Confirm_belanja.php, Penyimpanan.php - actually exists on disk.
rem  CodeIgniter's Upload library does NOT create its destination folder -
rem  if it's missing, do_upload fails silently, which is exactly what caused
rem  the "foto tidak tampil" / "gagal upload" bugs.
rem
rem  Safe to re-run any time - after a fresh deploy, after pulling this repo,
rem  as a scheduled health check, etc: folders that already exist are left
rem  untouched, only the missing ones get created.
rem
rem  NOTE: do not put parentheses inside any "echo" text below. cmd.exe scans
rem  for "(" / ")" to find where an if/for block ends, even inside a quoted-
rem  looking echo line, so a literal "(" or ")" in an echoed string silently
rem  breaks the block it sits in.
rem ============================================================================

rem Resolve the project root as the folder this script lives in.
set "ROOT=%~dp0"
set "UPLOAD_DIR=%ROOT%upload"

rem Keep this list in sync with every "upload_path" => "./upload/<x>/" in the
rem application/controllers/*.php files.
set FOLDERS=bukti_belanja_digital foto_barang_koperasi bukti_belanja_manual bukti_penyerahan_uang foto_bukti foto_diri ktp kk super

echo.
echo Checking upload folders under: %UPLOAD_DIR%
echo.

if not exist "%UPLOAD_DIR%" (
    echo [CREATE] upload - base folder was missing
    mkdir "%UPLOAD_DIR%"
)

set MISSING_COUNT=0
set OK_COUNT=0

for %%F in (%FOLDERS%) do (
    if exist "%UPLOAD_DIR%\%%F\" (
        echo [OK]     %%F
        set /a OK_COUNT+=1
    ) else (
        echo [CREATE] %%F - was missing
        mkdir "%UPLOAD_DIR%\%%F"
        if exist "%UPLOAD_DIR%\%%F\" (
            set /a MISSING_COUNT+=1
        ) else (
            echo          FAILED to create %%F - check folder permissions
        )
    )
)

echo.
if %MISSING_COUNT%==0 (
    echo Semua folder upload sudah lengkap. Tidak ada yang perlu dibuat.
) else (
    echo Selesai: %MISSING_COUNT% folder yang sebelumnya hilang sudah dibuat, %OK_COUNT% folder lain sudah OK.
)
echo.

endlocal
