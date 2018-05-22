$(document).ready(function(){
	$(".subnav-items").eq(0).children(".subnav-item").eq(0).css("margin-left","232px");
	$(".subnav-items").eq(1).children(".subnav-item").eq(0).css("margin-left","486px");
	$(".subnav-items").eq(2).children(".subnav-item").eq(0).css("margin-left","886px");
});

function navOver(index){
	$(".nav-item").removeClass("nav-active");
	$(".nav-item").eq(parseInt(sessionStorage.getItem('aindex'))).addClass("nav-active");
	$(".subnav-items").css("display","none");
	if(index==1||index==2||index==5){
		$(".subnav").css("display","block");
		if(index==1){
			$(".subnav-items").eq(0).css("display","inline-block");
		}
		if(index==2){
			$(".subnav-items").eq(1).css("display","inline-block");
		}
		if(index==5){
			$(".subnav-items").eq(2).css("display","inline-block");
		}
	}else{
		$(".subnav").css("display","none");
	}
}
function navOut(index){
	if(index==1||index==2||index==5){
		if($(".nav-item").eq(index).is('.nav-item.nav-visit')){
			$(".subnav").css("display","none");
		}
	}
}
function navTo(index){
	sessionStorage.setItem('operate-index',0);
	if(index==0){
		window.location.href="index.html";
	}
	if(index==3){
		window.location.href="operate.html";
	}
}

function subnavEnter(index){
	$(".nav-item").removeClass("nav-active");
	$(".nav-item").eq(parseInt(sessionStorage.getItem('aindex'))).addClass("nav-active");
	$(".nav-item").eq(index).addClass("nav-active");
}
function subnavLeave(){
	$(".nav-item").removeClass("nav-active");
	$(".nav-item").eq(parseInt(sessionStorage.getItem('aindex'))).addClass("nav-active");
	$(".subnav").css("display","none");
}
function subnavTo(index){
}