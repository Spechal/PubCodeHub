@echo on

%windir%\system32\ftp.exe -n -s:"%~f0" domain.com
goto done
user someuser
somepassword
cd /home/user/public_html
ls -al
quit
:done
pause