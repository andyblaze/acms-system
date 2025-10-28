@echo off
if "%~1"=="" (
  echo Usage: gitpush.bat "commit message"
  exit /b 1
)
echo Backing up database...
"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump" -u roe09 -proe09 ___acms > backup.sql
git add .
git commit -m "%~1"
git push origin main
echo %TIME%