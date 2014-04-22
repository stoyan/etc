// https://developer.mozilla.org/en-US/docs/Web/JavaScript/
//         Reference/Functions_and_function_scope/rest_parameters

function fun1(...theArgs) {
  console.log(theArgs.length);
}

fun1();  // 0
fun1(5); // 1
fun1(5, 6, 7); // 3