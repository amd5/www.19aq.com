echo off
echo git cmd 目录必须设置为系统环境变量
set "dir=%~dp0"
cd %dir%
echo 按任意键继续git pull代码
pause
git pull 2>&1
pause