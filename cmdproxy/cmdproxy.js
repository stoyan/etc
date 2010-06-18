/**
 * Command proxy
 *
 * Executes a program without the command prompt window
 *
 * Compile with .NET's JScript compiler
 * for testing: > jsc cmdproxy.js
 * production:  > jsc /t:winexe cmdproxy.js
 *
 * @version 1.0
 * @author Stoyan Stefanov
 * @license Dedicated to the public domain
 */

import System;
import System.Diagnostics

var ps: Process,
    psinfo: ProcessStartInfo,
    program,
    args;

if (typeof print === "undefined") { // no "print()" in win exe
  var print = function(what){};
}

// get arguments sans cmdproxy.exe itself
args = Environment.GetCommandLineArgs().slice(1);
if (!args[0]) {
    print("Gimme a program to run");
    Environment.Exit(1);
}
// program to run
program = args[0];
// arguments to pass to the program
args = '"' + args.slice(1).join('" "') + '"';

// process setup
psinfo = new ProcessStartInfo();
//psinfo.UseShellExecute = false;
psinfo.FileName = program;
psinfo.WindowStyle = ProcessWindowStyle.Hidden;
if (args) {
    psinfo.Arguments = args;
}

// run!
try {
    ps = Process.Start(psinfo);
    ps.WaitForExit();
    ps.Close();
  
} catch (e) {
    print("Cound't start `" + program + "` with " + ((args) ? "arguments: " + args : "no arguments"));
    print(e);
}