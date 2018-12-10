<div id="container" style="overflow-x:scroll;overflow-y:scroll;height: 100%;width:100%;">
<canvas id="cvas" width="7264" height="11464"></canvas>
<div>

<script>

var img = document.createElement("img");

img.onload = function(e){

// uppercase = source, lowercase = target
var x1 = 6367.0;
var y1 = 4336.0;

var X1 = 45.463377;
var Y1 = 10.850847;

var x2 = 11460.0;
var y2 = 3365.0;

var X2 = 45.469903;
var Y2 = 10.906154;

var x3 = 6464.0;
var y3 = 6606.0;

var X3 = 45.446075;
var Y3 = 10.851344;

var b = ((x1-x2)*(X2-X3)-(x2-x3)*(X1-X2)) / ((Y1-Y2)*(X2-X3)-(Y2-Y3)*(X1-X2));
var a = (x1-x2-b*(Y1-Y2))/(X1-X2);
var c = x3-a*X3-b*Y3;

var e = ((y1-y2)*(X2-X3)-(y2-y3)*(X1-X2)) / ((Y1-Y2)*(X2-X3)-(Y2-Y3)*(X1-X2));
var d = (y1-y2-e*(Y1-Y2))/(X1-X2);
var f = y3-d*X3-e*Y3;

var X = <?php echo $_GET["X"]?>;
var Y = <?php echo $_GET["Y"]?>;

var imgwidth = img.width;
var imgheight = img.height;
	
var x = (a*X+b*Y+c) / 11464.0 * imgwidth;
var y = (d*X+e*Y+f) / 7264.0 * imgheight;

var context = document.getElementById("cvas").getContext("2d");
var width = document.getElementById("cvas").width;
var height = document.getElementById("cvas").height;

context.drawImage(img, 0, 0);

context.fillStyle="blue";
context.beginPath();
context.arc(x,y,10,0,2*Math.PI);
context.fill();

var container = document.getElementById("container");
document.getElementById("container").scrollTo(x - container.clientWidth /2, y - container.clientHeight/2);
}

img.src="../../resources/img/map/bussolengo.png";
</script>