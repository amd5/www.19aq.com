layui.use('element', function() {
	var element = layui.element;
	//监听导航点击
	element.on('nav(layadmin-system-side-menu)', function(elem) {
		//console.log(elem)
		//layer.msg(elem.text());
	});
});

$(function()
{
	crumbs();
	
	initPage();
	
	$("#LAY_app_flexible").click(function()
	{
		if($(this).hasClass("layui-icon-shrink-right"))
		{
			$("#LAY_app").addClass("layadmin-side-shrink");
			$(this).removeClass("layui-icon-shrink-right");
			$(this).addClass("layui-icon-spread-left");
		}
		else
		{
			$("#LAY_app").removeClass("layadmin-side-shrink");
			$(this).removeClass("layui-icon-spread-left");
			$(this).addClass("layui-icon-shrink-right");
		}
	});
	$(".layui-icon-refresh-3").click(function(){document.location=document.location;});
	$("a[layadmin-event='fullscreen']").click(function()
	{
		if($(this).find("i").hasClass("layui-icon-screen-full"))
		{
			$(this).find("i").removeClass("layui-icon-screen-full");
			$(this).find("i").addClass("layui-icon-screen-restore");
			fullScreen();
		}
		else
		{
			$(this).find("i").addClass("layui-icon-screen-full");
			$(this).find("i").removeClass("layui-icon-screen-restore");
			exitFullScreen();
		}
	});
	
	$("#admin-info").hover(function()
	{
		$(this).find(".layui-nav-child").addClass("layui-show");
		$(this).find(".layui-nav-more").addClass("layui-nav-mored");
	},
	
	function()
	{
		$(".layui-nav-more").removeClass("layui-nav-mored");
		$(".layui-nav-child").removeClass("layui-show");
	});
	
	$("a").click(function()
	{
		var href=$(this).attr("lay-href");
		
		if(href!=null && href!="")
		{
			document.location=href;
		}
	});
	
	$(".layui-header .layui-nav li").hover(function()
	{
		var w=$(this).width();
		var h=$(this).height();
		$(this).parent().find(".layui-nav-bar").css("left",$(this).position().left+10);
		$(this).parent().find(".layui-nav-bar").css("width",w);
		$(this).parent().find(".layui-nav-bar").css("opacity","1");
	},function()
	{
		$(this).parent().find(".layui-nav-bar").css("left",0);
		$(this).parent().find(".layui-nav-bar").css("width",0);
		$(this).parent().find(".layui-nav-bar").css("opacity","0");
	});
	
	$(".layui-side-menu .layui-nav li").hover(function()
	{
		var w=$(this).width();
		var h=$(this).height();
		$(this).parent().find(".layui-nav-bar").css("top",$(this).position().top+10);
		$(this).parent().find(".layui-nav-bar").css("height",46);
		$(this).parent().find(".layui-nav-bar").css("opacity","1");
		if($("#LAY_app").hasClass("layadmin-side-shrink"))
		{
			//$(this).find("a").attr("lay-tips")
		    layer.tips($(this).find("a").attr("lay-tips"),$(this));
		}
	},function()
	{
		$(this).parent().find(".layui-nav-bar").css("top",0);
		$(this).parent().find(".layui-nav-bar").css("height",46);
		$(this).parent().find(".layui-nav-bar").css("opacity","0");
		layer.closeAll();
	});
	
});
function crumbs()
{
	try
	{
		var href=document.location+"";
		
		var mdefault=null;
		
		$(".layui-nav-child").each(function(index, element) 
		{
			   var pt=$(this);
			   
			   $(this).find("dd").each(function()
			   {
				   var data_jump=$(this).attr("data-jump");
				   
				   if(data_jump!=null && data_jump!="")
				   {
					   if(data_jump=="/")
					   {
						   mdefault=$(this);
					   }
					   else
					   {
						   if(href.indexOf(data_jump)>0)
						   {
							   mdefault=$(this);
							   
							   $(this).addClass("layui-this");
							   
							   $(pt).parents("li.layui-nav-item").addClass("layui-nav-itemed");
							   
						   }
					   }
				   }
			   });
        });
		
		if(mdefault!=null)
		{
			var t1=$("#LAY-system-side-menu").find(".layui-nav-itemed").find("a[lay-direction='2']").text();
		
			var t2="";
			$("#LAY-system-side-menu").find(".layui-nav-child").find("dd").each(function(index, element) 
			{
				  if($(this).hasClass("layui-this")) 
				  {
					  t2=$(this).find("a").text();
				  }
			});;
			
			$(".layui-breadcrumb").html('<a lay-href="">'+t1+'</a><span lay-separator="">/</span><a><cite>'+t2+'</cite></a>');
		}
	}
	catch(e)
	{
	}
}
function initPage()
{
	try
	{
		if($("#laypage").length>0)
		{
			var initrun=0;
			layui.use(['laypage', 'layer'], function()
			{
				var laypage = layui.laypage,layer = layui.layer;
		  
				laypage.render({
				elem: 'laypage'
				,count: $("#recordcount").val()
				,limit:$("#pagelimit").val()
				,curr:$("#pagecurr").val()
				,layout: ['count', 'prev', 'page', 'next', 'limit', 'refresh', 'skip']
				,jump: function(obj)
				{
				  //console.log(obj)
				  if(initrun==1)
				  {
					  $("#pagecurr").val(obj.curr);
					  
					  $("#pagelimit").val(obj.limit);
				     
					  $("#layform").submit();
				  }
				   
				  initrun=1;
				}
			  });
			});
		}
	}
	catch(e)
	{
	}
}
/*
 * 浏览器全屏
 */
function fullScreen() {

	  var el = document.documentElement;

	  var rfs = el.requestFullScreen || el.webkitRequestFullScreen;

	  if(typeof rfs != "undefined" && rfs) {

	    rfs.call(el);

	  } else if(typeof window.ActiveXObject != "undefined") {

	    var wscript = new ActiveXObject("WScript.Shell");

	    if(wscript != null) {

	        wscript.SendKeys("{F11}");

	    }

	}else if (el.msRequestFullscreen) {

		el.msRequestFullscreen();

	}else if(el.oRequestFullscreen){
		
		el.oRequestFullscreen();
		
    }else{
    	
    	swal({   title: "浏览器不支持全屏调用！",   text: "请更换浏览器或按F11键切换全屏！(3秒后自动关闭)", type: "error",  timer: 3000 });	
	       
    }

}

/*
 * 浏览器退出全屏
 */
function exitFullScreen() {

	  var el = document;

	  var cfs = el.cancelFullScreen || el.webkitCancelFullScreen || el.exitFullScreen;

	  if(typeof cfs != "undefined" && cfs) {

	    cfs.call(el);

	  } else if(typeof window.ActiveXObject != "undefined") {

	    var wscript = new ActiveXObject("WScript.Shell");

	    if(wscript != null) {

	        wscript.SendKeys("{F11}");

	    }

	}else if (el.msExitFullscreen) {

		el.msExitFullscreen();

	}else if(el.oRequestFullscreen){
		
		el.oCancelFullScreen();
		
    }else{ 
   	
    	swal({   title: "浏览器不支持全屏调用！",   text: "请更换浏览器或按F11键切换全屏！(3秒后自动关闭)", type: "error",  timer: 3000 });	
    }  
	  
}