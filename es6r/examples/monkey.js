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

var monkey = new Monkey('Monkey');

console.log([
  monkey.name,
  monkey.see(),
  '-',
  monkey.name,
  monkey.do(),
].join(' '));