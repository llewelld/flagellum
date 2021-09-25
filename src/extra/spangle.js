var glows = 40;
var x = new Array (glows);
var y = new Array (glows);
var age = new Array (glows);
var agemax = new Array (glows);
var speed = new Array (glows);
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
  agemax[i] = 5000 + Math.random () * 9000;
  speed[i] = Math.random () * 0.1;
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
  var rotate;

  time = date.getTime ();
  deltatime = time - prevtime;
  getdimensions ();

  hwidth = wwidth / 2;
  hheight = wheight / 2;
  
  fade += (0.00015 * deltatime);
  if (fade > 1.0) {
    fade = 1.0;
  }

  rotate = Math.floor (((360 * (time / 6000)) % 90) / 10);

  for (i = 0; i < glows; i++) {
    age[i] += deltatime;
    if (age[i] > agemax[i]) {
      resetspangle (i);
    }
    //y[i] += deltatime * speed[i];

    op = agefunc (age[i] / agemax[i]);
    xsize = 32 * op;
    ysize = 32 * op;
    xpos = x[i] - (xsize / 2);
    ypos = y[i] - (ysize / 2);

    xpos = Math.min (xpos, clipwidth);
    ypos = Math.min (ypos, clipheight);

    width = Math.min (xsize, clipwidth - xpos);
    height = Math.min (ysize, clipheight - ypos);

    image = "glowi" + i;
    name = "glow" + i;
    document.getElementById (image).src = imageanim[rotate].src;
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

  for (i = 0; i < 9; i++) {
    imageanim[i] = new Image();
    imageanim[i].src = "./extra/spangle02-0" + i + "0.png";
  }
  
  for (i = 0; i < glows; i++) {
    document.write ('<div id="glow' + i + '" style="position:absolute;overflow:hidden;left:0px;top:0px;width:32px;height:32px;visibility:visible;z-index:-100;opacity:1.0;filter:alpha(opacity=100);border:0;padding:0;margin:0;"><img id="glowi' + i + '" src="./extra/spangle02.png" width="32" height="32" border="0" style="position:absolute;left:0px;top:0px;border:0;padding:0;margin:0;" /></div>');
  }

  if (window.addEventListener) {
    window.addEventListener ('load', startmove, false);
  }
  else if (window.attachEvent) {
    window.attachEvent ('onload', startmove);
  }
}

insertglows ();

