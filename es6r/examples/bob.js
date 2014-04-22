class Monkey {
  constructor(name) {
    this.name = name;
  }
  see() {
    return 'see';
  }
  do() {
    return 'doo';
  }
}


class Person extends Monkey{
  constructor(name) {
    super(name || "Monkey");
  }
}


var bob = new Person;

console.log([
  bob.name,
  bob.see(),
  '-',
  bob.name,
  bob.do(),
].join(' '));