REM This will generate the zipfiles for PlanArchiv in /build/packages
REM This needs the zip binaries from Info-Zip installed. An installer can be found http://gnuwin32.sourceforge.net/packages/zip.htm
setlocal
SET PATH=%PATH%;C:\Program Files (x86)\GnuWin32\bin
rmdir /q /s packages
rmdir /q /s package
mkdir packages
mkdir package

REM Component
cd ../com_planarchiv/
zip -r ../build/packages/com_planarchiv.zip *
copy ..\build\packages\com_planarchiv.zip ..\build\package

REM Package
cd ../build/package/
copy ..\..\pkg_planarchiv.xml
zip pkg_planarchiv.zip *
del pkg_planarchiv.xml
copy pkg_planarchiv.zip ..\packages
cd ..
rmdir /q /s package
exit