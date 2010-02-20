This is a command proxy, it executes other programs 
without the command prompt window

@version 1.0
@author Stoyan Stefanov
@license Dedicated to the public domain


COMPILING
---------
Compile with .NET's JScript compiler
- for testing: > jsc cmdproxy.js
- production:  > jsc /t:winexe cmdproxy.js

/t:winexe is the magic that doesn't bring up the 
command prompt window

RUNNING
---------
Run cmdproxy.exe passing the other program you need to run
plus any parameters you need to pass to the other program

> cmdproxy.exe "C:\Program File\my.exe" param1 -p value2

Be aware that the program you run needs to terminate.
Otherwise if you run say Notepad (> cmdproxy.exe notepad),
it doesn't terminate and there's no window for you to close.
So (CTRL+ALD+DEL) you need to find and kill the notepad.exe 
process manually.