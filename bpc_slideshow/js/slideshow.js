var index = 0;
var pindex = 0;
var data_arr = [];
var content_arr = [];
var img_arr = [];
var odd = 0;
var fi ="seg1";
var si = "seg2";
var overlap = false;
var duration = 0;
var width = 0;
var height =0;
var imgwidth =0;
var imgheight = 0;
var onlyimages = 0;
var base_url = "";
$("#document").ready(
function() {

	var loader = new Image();
	loader.src = base_url+"/wp-content/plugins/bpc_slideshow/loader.gif";
	$("#seg2").html('<div align="center"><img src="'+loader.src+'" border="0" style="padding:10px;"></div>');
  	$("#seg1").css("height",imgheight+"px");
  	var position=$("#seg1").position();
  	if(position) {
	  	$("#seg2").css("left",position.left+"px");
	  	$("#seg2").css("top",position.top+"px");
	 	$("#seg2").css("height",imgheight+"px");
		if(onlyimages ==0) {
		 	$("#slideshow").css("width",width+"px");
		 }else {
		 	$("#slideshow").css("width",imgwidth+"px");
		 }
	 	
		$("#slideshow").css("height",imgheight+"px");
		setpos();		
		
		$.get(content_url, function(data) {
			var content_data = data.split('[IMAGES]');
			var data_cnt = content_data[1].split('[END]');
			img_arr = content_data[0].split(',');
	  		for(i=0;i<img_arr.length;++i) {
				var tmp = new Image();
				tmp.src = img_arr[i];
				if(onlyimages ==0) {
					data_arr.push('<div style="float:left;padding-right:10px;"><img src="'+tmp.src+'"   width="'+imgwidth+'" height="'+imgheight+'"></div>'+data_cnt[i]);
				}else {
					data_arr.push('<div style="float:left;padding-right:10px;"><img src="'+tmp.src+'"   width="'+imgwidth+'" height="'+imgheight+'"></div>');				
				}
			}			
			runner();
		});
	}


});

function setpos() {
	var xy = YAHOO.util.Dom.getXY('slideshow');
	//var xy = YAHOO.util.Dom.getXY('seg1');
	YAHOO.util.Dom.setXY('seg1', xy)
	YAHOO.util.Dom.setXY('seg2', xy)

}


function runner() {
	
	if(index == data_arr.length) {
		index =0;
	}
	
	odd = (odd ==0)?1:0;
	
	if(odd) {
		$("#"+si).animate({opacity: 0},duration,
			function() {
				$("#"+si).html(data_arr[index]);
				setpos();
				setTimeout("runner()",duration);
				index = index + 1;
			}
		);
	}else {
		$("#"+si).animate({opacity: 1},duration,
			function() {
				$("#"+fi).html(data_arr[index]);
				setpos();
				setTimeout("runner()",duration);
				index = index + 1;
			}
		);
	}
	
}

function next_index()  {
	if(index == data_arr.length) {
		index =0;
	}
	index = index + 1;
}
