var glows = 50;
var x = new Array (glows);
var y = new Array (glows);
var age = new Array (glows);
var agemax = new Array (glows);
var xspeed = new Array (glows);
var yspeed = new Array (glows);
var maxsize = new Array (glows);
var rotate = new Array (glows);
var rspeed = new Array (glows);
var imageanim = new Array (9);

var wwidth = 0;
var wheight = 0;
var clipwidth = 0;
var clipheight = 0;
var fade = 0.0;
var deltatime;
var prevtime;
var ageconst;
var agexscale;
var ageyscale;

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

function agefunc (x) {
  return ageyscale * Math.max (1 - (ageconst / (x + ageconst)) - (agexscale * x), 0);
}

function startmove () {
  var i;
  var date = new Date ();
  var inflectionx;
  var inflectiony;
  var inject;

  inject = "";
  for (i = 0; i < glows; i++) {
    inject += '<div id="glow' + i + '" class="animate" style="left:-100px;top:-100px;width:32px;height:32px;opacity:1.0;filter:alpha(opacity=100);border:0;padding:0;margin:0;"><img id="glowi' + i + '" src="./images/animate/stars/Star000.png" width="32" height="32" border="0" style="position:absolute;left:0px;top:0px;border:0;padding:0;margin:0;" /></div>\n';
  }
  document.getElementById ("animate").innerHTML = inject;
  inject = "";

  fade = 0.0;
  prevtime = date.getTime ();
  getdimensions ();

  ageconst = 0.2;
  agexscale = 1 - (ageconst / (1 + ageconst));
  inflectionx = Math.sqrt (ageconst + (ageconst * ageconst)) - ageconst;
  ageyscale = 1;
  inflectiony = agefunc (inflectionx);
  ageyscale = 0.5 / inflectionx;
    
  for (i = 0; i < glows; i++) {
    resetspangle (i);
  }
  movemenu();
}

function resetspangle (i) {
  x[i] = Math.random() * clipwidth;
  y[i] = Math.random() * clipheight;
  agemax[i] = 4000 + Math.random () * 5000;
  xspeed[i] = (Math.random () * 0.1) - 0.05;
  yspeed[i] = (Math.random () * 0.1) - 0.05;
  maxsize[i] = 6 + Math.random () * 64;
  rotate[i] = Math.random () * 360;
  rspeed[i] = (Math.random () * 20) - 10;
  age[i] = 0;
}

function movemenu()
{
  var hwidth;
  var hheight;
  var xpos;
  var ypos;
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

  time = date.getTime ();
  deltatime = time - prevtime;
  getdimensions ();

  hwidth = wwidth / 2;
  hheight = wheight / 2;
  
  fade += (0.00015 * deltatime);
  if (fade > 1.0) {
    fade = 1.0;
  }

  for (i = 0; i < glows; i++) {
    age[i] += deltatime;
    if (age[i] > agemax[i]) {
      resetspangle (i);
    }
    x[i] += deltatime * xspeed[i];
    y[i] += deltatime * yspeed[i];
    yspeed[i] += deltatime * 0.00001;

    op = 0.7 * agefunc (age[i] / agemax[i]);
    xsize = maxsize[i] * op;
    ysize = maxsize[i] * op;
    xpos = x[i] - (xsize / 2);
    ypos = y[i] - (ysize / 2);

    xpos = Math.min (xpos, clipwidth);
    ypos = Math.min (ypos, clipheight);

    width = Math.min (xsize, clipwidth - xpos);
    height = Math.min (ysize, clipheight - ypos);

    rotate[i] += rspeed[i];
    rotate[i] = (360 + rotate[i]) % 360;
    anim = Math.floor (rotate[i] / 10);

    image = "glowi" + i;
    name = "glow" + i;
    document.getElementById (image).src = imageanim[anim].src;
    document.getElementById (image).style.width = xsize + "px";
    document.getElementById (image).style.height = ysize + "px";
    document.getElementById (name).style.left = xpos + "px";
    document.getElementById (name).style.top = ypos + "px";
    document.getElementById (name).style.width = width + "px";
    document.getElementById (name).style.height = height + "px";
    document.getElementById (name).style.opacity = op;
    document.getElementById (name).style.filter = "alpha(opacity=" + (op * 100) + ")";
  }
  Id = window.setTimeout ("movemenu();", 50);
  prevtime = time;
}

function insertglows() {
  var i;

  for (i = 0; i < 36; i++) {
    imageanim[i] = new Image();
    imageanim[i].src = "./images/animate/stars/Star"
    if (i < 10) {
      imageanim[i].src += 0;
    }
    imageanim[i].src += i + "0.png";
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

