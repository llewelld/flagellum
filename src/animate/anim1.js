var rotations = 36;
var rotstep = 10;
var linepic = new Array (rotations);

var lines = 5;
var linestructs = 20;
var linestruct = new Array (linestructs);

var wwidth = 0;
var wheight = 0;
var clipwidth = 0;
var clipheight = 0;
var deltatime;
var prevtime;

function getdimensions () {
  if (document.documentElement.clientWidth) {
    wwidth = document.documentElement.clientWidth;
    wheight = document.documentElement.clientHeight;
  }
  else if (typeof (window.innerWidth) == 'number') {
    wwidth = window.innerWidth;
    wheight = window.innerHeight;
  }
  else {
    wwidth = document.body.clientWidth;
    wheight = document.body.clientHeight;
  }

  if (window.pageXOffset) {
    clipwidth = wwidth + window.pageXOffset;
    clipheight = wheight + window.pageYOffset;
  }
  else {
    clipwidth = wwidth + document.documentElement.scrollLeft
    clipheight = wheight + document.documentElement.scrollTop;
  }
}

function makestructures () {
  var i;
  var j;
  
  for (i = 0; i < linestructs; i++) {
    linestruct[i] = new Object;
    linestruct[i].name = i;
    linestruct[i].xpos = wwidth / 2;
    linestruct[i].ypos = wheight / 2;
    linestruct[i].direction = 0;
    linestruct[i].lines = lines;
    linestruct[i].x = new Array (linestruct.lines);
    linestruct[i].y = new Array (linestruct.lines);
    linestruct[i].nextline = 0;
    linestruct[i].count = i;

    for (j = 0; j <linestruct.lines; j++) {
      linestruct[i].x[j] = 0;
      linestruct[i].y[j] = 0;
    }
  }
}

function startmove () {
  var i;
  var date = new Date ();
  var inflectionx;
  var inflectiony;
  var inject;

  inject = "";
  for (i = 0; i < linestructs; i++) {
    for (j = 0; j < lines; j++) {
      image = "li" + i + "-" + j;
      name = "l" + i + "-" + j;
      inject += '<div id="' + name + '" class="animate" style="left:-100px;top:-100px;width:' + linepic[0].width + 'px;height:' + linepic[0].height + 'px;opacity:0.5;filter:alpha(opacity=50);border:0;padding:0;margin:0;"><img id="' + image + '" src="' + linepic[0].src + '" width="' + linepic[0].width + '" height="' + linepic[0].height + '" border="0" style="position:absolute;left:0px;top:0px;border:0;padding:0;margin:0;" /></div>\n';
    }
  }
  document.getElementById ("animate").innerHTML = inject;
  inject = "";

  prevtime = date.getTime ();
  getdimensions ();
  makestructures ();

  movemenu();
}

function updateline (line) {
  var anglepic;
  var radians;
  var xmove;
  var ymove;
  var i;
  var cycle;

  line.count++;
  line.direction += (16 * Math.sin (line.count / 5)) + (7 * Math.cos (line.count / 41));
  line.direction = (360 + line.direction) % 360;
  anglepic = Math.round ((line.direction) / rotstep) % rotations;
  radians = line.direction * Math.PI / 180.0;

  xmove = Math.round (16 * Math.cos (radians))
  ymove = Math.round (16 * Math.sin (radians))

  image = "li" + line.name + "-" + line.nextline;
  name = "l" + line.name + "-" + line.nextline;
  document.getElementById (image).src = linepic[anglepic].src;
  document.getElementById (image).style.width = linepic[anglepic].width + "px";
  document.getElementById (image).style.height = linepic[anglepic].height + "px";
  if (xmove < 0) {
    document.getElementById (name).style.left = (line.xpos + 2 - linepic[anglepic].width) + "px";
    line.xpos -= linepic[anglepic].width - 3;
  }
  else {
    document.getElementById (name).style.left = (line.xpos - 2) + "px";
    line.xpos += linepic[anglepic].width - 3;
  }
  if (ymove < 0) {
    document.getElementById (name).style.top = (line.ypos + 2 - linepic[anglepic].height) + "px";
    line.ypos -= linepic[anglepic].height - 3;
  }
  else {
    document.getElementById (name).style.top = (line.ypos - 2) + "px";
    line.ypos += linepic[anglepic].height - 3;
  }
 
  if (line.xpos > (clipwidth - 20)) {
    line.xpos = 0;
  }
  if (line.xpos < 0) {
    line.xpos = (clipwidth - 20);
  }
  if (line.ypos > (clipheight - 20)) {
    line.ypos = 0;
  }
  if (line.ypos < 0) {
    line.ypos = (clipheight - 20);
  }  
 
  document.getElementById (name).style.width = linepic[anglepic].width + "px";
  document.getElementById (name).style.height = linepic[anglepic].height + "px";

  for (i = 0; i < line.lines; i++) {
    cycle = (i + line.nextline) % line.lines
    image = "li" + line.name + "-" + cycle;
    name = "l" + line.name + "-" + cycle;
    op = 0.4 * (i / line.lines);
    document.getElementById (name).style.opacity = op;
    document.getElementById (name).style.filter = "alpha(opacity=" + (op * 100) + ")";
  }
 
  line.nextline = (line.nextline + 1) % line.lines;
}


function movemenu()
{
  var hwidth;
  var hheight;
  var date = new Date ();
  var width;
  var height;
  var xsize;
  var ysize;
  var op;
  var i;
  var name = "";
  var image = "";
  var ftype;
  var time;
  var anim;
  var anglepic;
  var radians;
  var xmove;
  var ymove;
  var line;
  var i;

  time = date.getTime ();
  deltatime = time - prevtime;
  getdimensions ();

  hwidth = wwidth / 2;
  hheight = wheight / 2;

  for (i = 0; i < linestructs; i++) {
    updateline (linestruct[i]);
  }

  Id = window.setTimeout ("movemenu();", 70);
  prevtime = time;
}

function insertglows() {
  var i;
  var angle;
  var name;
  var image;

  angle = 0;
  for (i = 0; i < rotations; i++) {
    radians = angle * Math.PI / 180;
    if (angle < 10) {
      name = "L00" + angle + "-016.png";
    }
    else if (angle < 100) {
      name = "L0" + angle + "-016.png";
    }
    else {
      name = "L" + angle + "-016.png";
    }

    linepic[i] = new Image();
    linepic[i].src = "./images/animate/lines/" + name;

    angle += rotstep;
  }
  
  if (window.addEventListener) {
    window.addEventListener ('load', startmove, false);
  }
  else if (window.attachEvent) {
    window.attachEvent ('onload', startmove);
  }
}

if (navigator.appName != "xMicrosoft Internet Explorer") {
  insertglows ();
}


