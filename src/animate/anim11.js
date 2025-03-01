var spooks = 6;
var frames = 15;
var a1 = new Array (spooks);
var a2 = new Array (spooks);
var size = new Array (spooks);
var opacity = new Array (spooks);
var frame = new Array (spooks);
var ftypes = 6;
var fname = new Array ("spook1.png", "spook2.png", "spook3.png", "spook4.png", "spook5.png", "spook6.png", "spook7.png", "spook8.png", "spook9.png", "spook10.png", "spook11.png", "spook12.png", "spook13.png", "spook14.png", "spook15.png");
var fxysize = new Array (600, 600, 600, 600, 600, 600);
var fade = 0.0;
var deltatime;
var prevtime;
var wwidth = 0;
var wheight = 0;
var clipwidth = 0;
var clipheight = 0;

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
    clipwidth = wwidth + document.documentElement.scrollLeft;
    clipheight = wheight + document.documentElement.scrollTop;
  }
  wheight = document.documentElement.scrollHeight;
  clipheight = document.documentElement.scrollHeight;
}

function startmove () {
  var i;
  var date = new Date ();
  var inject;

  inject = "";
  for (i = 0; i < spooks; i++) {
    ftype = i;
    frame[i] = (i * 7) % frames;
    inject += '<div id="spook'+i+'" class="animate" style="z-index:100;pointer-events:none;overflow:hidden;position:absolute;left:0px;top:0px;width:' + fxysize[ftype] + 'px;height:' + fxysize[ftype] + 'px;opacity:0.0;filter:alpha(opacity=0)"><img id="spooki'+i+'" src="./images/animate/spooky/' + fname[frame[i]] + '" width="' + fxysize[ftype] + '" height="' + fxysize[ftype] + '"></div>\n';
  }
  document.getElementById ("animate").innerHTML = inject;
  inject = "";

  fade = 0.0;
  prevtime = date.getTime ();

  for (i = 0; i < spooks; i++) {
    a1[i] = Math.random() / 10000;
    a2[i] = Math.random() / 100000;
    size[i] = Math.random() / 5000;
    opacity[i] = Math.random() / 5000;
  }
  movemenu();
}

function movemenu () {
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

  time = date.getTime ();
  deltatime = time - prevtime;
  getdimensions ();

  hwidth = wwidth / 2;
  hheight = wheight / 2;

  fade += (0.0002 * deltatime);
  if (fade > 0.75) {
    fade = 0.75;
  }

  for (i = 0; i < spooks; i++) {
    ftype = i % ftypes;

    frame[i] = (frame[i] + 0.5) % frames;
    xysize = fxysize[ftype] * (((Math.sin (size[i] * time)) + 3) / 4);
    xpos = hwidth + (hwidth * (Math.sin (a1[i] * time))) - (xysize / 2);
    ypos = hheight + (hheight * (Math.sin (a2[i] * time))) - (xysize / 2);
    op = fade * (((Math.sin (opacity[i] * time)) + 1) / 3);
   
    xpos = Math.min (xpos, clipwidth);
    ypos = Math.min (ypos, clipheight);

    width = Math.min (xysize, clipwidth - xpos);
    height = Math.min (xysize, clipheight - ypos);

    image="spooki"+i;
    name="spook"+i;
    document.getElementById (image).style.width = xysize + "px";
    document.getElementById (image).style.height = xysize + "px";
    document.getElementById (name).style.left = xpos + "px";
    document.getElementById (name).style.top = ypos + "px";
    document.getElementById (name).style.width = width + "px";
    document.getElementById (name).style.height = height + "px";
    document.getElementById (name).style.opacity = op;
    document.getElementById (name).style.filter = "alpha(opacity=" + (op * 100) + ")";
    document.getElementById (image).src = "./images/animate/spooky/" + fname[Math.floor(frame[i])];
  }
  Id = window.setTimeout ("movemenu();", 50);
  prevtime = time;
}

function insertspooks() {
  if (window.addEventListener) {
    window.addEventListener ('load', startmove, false);
  }
  else if (window.attachEvent) {
    window.attachEvent ('onload', startmove);
  }
}

if (navigator.appName != "xMicrosoft Internet Explorer") {
  insertspooks ();
}

