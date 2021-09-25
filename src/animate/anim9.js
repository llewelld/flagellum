var rotations = 36;
var rotstep = 10;
var linepic = new Array (rotations);

var lines = 9;
var linestructs = 10;
var linestruct = new Array (linestructs);

var wwidth = 0;
var wheight = 0;
var clipwidth = 0;
var clipheight = 0;
var deltatime;
var prevtime;

var mousex = 0;
var mousey = 0;
var mouseover = 0;

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
    linestruct[i].xpos = 20 + (i * (clipwidth - 200) / linestructs);
    linestruct[i].ypos = 116;
    linestruct[i].direction = 0;
    linestruct[i].lines = lines;
    linestruct[i].x = new Array (linestruct.lines);
    linestruct[i].y = new Array (linestruct.lines);
    linestruct[i].nextline = 0;
    linestruct[i].count = 0;
    linestruct[i].size = 0;

    for (j = 0; j <linestruct.lines; j++) {
      linestruct[i].x[j] = 0;
      linestruct[i].y[j] = 0;
    }
  }
}

function startmove () {
  var i;
  var j;
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
    image = "fi" + i;
    name = "f" + i;
    inject += '<div id="' + name + '" class="animate" style="left:-100px;top:-100px;width:40px;height:40px;z-index:-99;opacity:1.0;filter:alpha(opacity=100);border:0;padding:0;margin:0;"><img id="' + image + '" src="./images/animate/firefly02.png" width="40" height="40" border="0" style="position:absolute;left:0px;top:0px;border:0;padding:0;margin:0;" /></div>\n';
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
  var theta;
  var glowsize;
  var distance;
  var overflow;

  line.count++;
  line.direction += (0.9 * (line.name + 1) * Math.sin (line.count / (line.name + 1))) + (1.1 * Math.cos (line.count / (1.2 * (linestructs - line.name))));

  if (mouseover > 0) {
    // Move towards mouse
    theta = line.direction - ((360 * Math.atan2 (mousey - line.ypos, mousex - line.xpos)) / Math.PI);
    theta = (360 + theta) % 360;
    if ((theta >= 0) && (theta < 180)) {
      line.direction -= 9;
    }
    else {
      line.direction += 9;
    }
  }

  line.direction = (360 + line.direction) % 360;


  anglepic = Math.round ((line.direction) / rotstep) % rotations;
  radians = line.direction * Math.PI / 180.0;

  xmove = Math.round (16 * Math.cos (radians))
  ymove = Math.round (16 * Math.sin (radians))

  name = "f" + line.name;
  image = "fi" + line.name;
  distance = Math.sqrt (((mousey - line.ypos) * (mousey - line.ypos)) + ((mousex - line.xpos) * (mousex - line.xpos)));
  if (mouseover > 0) {
    glowsize = 200 - (distance / 1);
  }
  else {
    glowsize = 32;
  }
  if (glowsize < 32) {
    glowsize = 32;
  }
  if (line.size > glowsize) {
    line.size -= 8;
  }
  else if (line.size < glowsize) {
    line.size += 12;
  }
  glowsize = line.size;
  
  document.getElementById (name).style.left = (line.xpos - (glowsize / 2.0)) + "px";
  document.getElementById (name).style.top = (line.ypos - (glowsize / 2.0)) + "px";

  overflow = (line.xpos + (glowsize / 2.0)) - clipwidth;
  if (overflow > 0) {
    document.getElementById (name).style.width = (glowsize - overflow) + "px";
  }
  else {
    document.getElementById (name).style.width = glowsize + "px";
  }

  overflow = (line.ypos + (glowsize / 2.0)) - clipheight;
  if (overflow > 0) {
    document.getElementById (name).style.height = (glowsize - overflow) + "px";
  }
  else {
    document.getElementById (name).style.height = glowsize + "px";
  }

  document.getElementById (image).style.width = glowsize + "px";
  document.getElementById (image).style.height = glowsize + "px";
  
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

function mouseentered(event)
{
  mouseover++;
}

function mouseleft(event)
{
  mouseover--;
}

function mousemove(event)
{
  mousex = event.clientX + document.documentElement.scrollLeft;
  mousey = event.clientY + document.documentElement.scrollTop;
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

  Id = window.setTimeout ("movemenu();", 60);
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
  document.captureEvents(Event.MOUSEMOVE);
  document.onmousemove = mousemove;

  document.captureEvents(Event.MOUSEOVER);
  document.onmouseover = mouseentered;
  document.onmouseout = mouseleft;
}

if (navigator.appName != "xMicrosoft Internet Explorer") {
  insertglows ();
}


