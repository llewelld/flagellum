var maxflakes=30;
var xpos=new Array(maxflakes);
var ypos=new Array(maxflakes);
var xmov=new Array(maxflakes);
var ymov=new Array(maxflakes);
var fre=new Array(maxflakes);
var amp=new Array(maxflakes);
var wpos=new Array(maxflakes);
var x;
var i;
var name="";
var frandom;
var ftypes=3
var fname=new Array ("flake1.png", "flake2.png", "flake3.png");
var fxsize=new Array (17, 22, 11);
var fysize=new Array (17, 22, 11);
var fmax=44;

function startmove()
{
  for (i=0; i<maxflakes; i++)
  {
    xpos[i]=Math.random()*(document.body.clientWidth-fmax);
    ypos[i]=Math.random()*(document.body.clientHeight-fmax);
    xmov[i]=Math.random()*4.0;
    ymov[i]=2.0+Math.random()*6.0;
    fre[i]=Math.random()*20;
    amp[i]=Math.random()*20.0;
    wpos[i]=Math.random()*7.0;
  }
  movemenu();
}

function movemenu()
{
  var wwidth=0;
  var wheight=0;
  var dwidth=0;
  var dheight=0;

  if (typeof(window.innerWidth) == 'number')
  {
    wwidth = window.innerWidth;
    wheight = window.innerHeight;
    dwidth = wwidth + window.pageXOffset;
    dheight = wheight + window.pageYOffset;
  }
  else if (document.documentElement.clientWidth)
  {
    wwidth = document.documentElement.clientWidth;
    wheight = document.documentElement.clientHeight;
    dwidth = wwidth + document.body.scrollLeft;
    dheight = wheight + document.body.scrollTop;
  }
  else
  {
    wwidth = document.body.clientWidth;
    wheight = document.body.clientHeight;
    dwidth = wwidth + document.body.scrollLeft;
    dheight = wheight + document.body.scrollTop;
  }

  for (i=0; i<maxflakes; i++)
  {
    xpos[i]=xpos[i]+xmov[i];
    ypos[i]=ypos[i]+ymov[i];
    wpos[i]+=0.01;
    if (wpos[i]>2*3.1415)
    {
      wpos[i]-=2*3.1415;
    }
    x=(xpos[i]+amp[i]*Math.sin(fre[i]*wpos[i]));
    if (x>(dwidth-fmax))
    {
      xpos[i]-=wwidth;
      x=(xpos[i]+amp[i]*Math.sin(fre[i]*wpos[i]));
    }
    if (ypos[i]>(dheight-fmax))
    {
      ypos[i]-=wheight;
    }
    name="flake"+i;
    document.getElementById(name).style.left=x+"px";
    document.getElementById(name).style.top=ypos[i]+"px";
  }
  Id = window.setTimeout("movemenu();",50);
}

function insertflakes()
{ 
  for (i=0; i<maxflakes; i++)
  {
    frandom = Math.floor (Math.random() * ftypes);
    document.write ('<div id="flake'+i+'" style="position:absolute;left:-' + fmax + 'px;top:0px;width:' + fxsize[frandom] + 'px;height:' + fysize[frandom] + 'px;visibility:visible;z-index:100;"><img src="./images/snow/' + fname[frandom] + '" width="' + fxsize[frandom] + '" height="' + fysize[frandom] + '"></div>');
  }

  if (window.addEventListener)
  {
    window.addEventListener ('load', startmove, false);
  }
  else if (window.attachEvent)
  {
    window.attachEvent ('onload', startmove);
  }
}

insertflakes();

