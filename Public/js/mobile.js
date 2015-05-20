$(document).ready(function() {
	$("#open_left").click(function(){
		$("#module_left").show();
		$("#module_left").animate({width: "100%"}, 300, function(){
			$("#module_left").show();
			$(".all-container").hide();
		});
	})
	$("#close_left").click(function(){
		$(".all-container").show();
		$("#module_left").animate({width: "0%"}, 300, function() {
			$(".all-container").show();
			$("#module_left").hide();
		});
	})
	
	$("#open_right").click(function(){
		showRight()
	})
	$("#close_right").click(function(){
		hideRight()
	})
})

function showRight(){
	$("#module_right").show();
	$("#module_right").animate({width: "100%"}, 300, function(){
		$("#module_right").show();
		$(".all-container").hide();
	});
}

function hideRight(){
	$(".all-container").show();
	$("#module_right").animate({width: "0%"}, 300, function(){
		$(".all-container").show();
		$("#module_right").hide();
	});
}

/**
 * 手机表情选择框
 * @param ev
 * @param o
 * @refer common.js
 */
function loadFaceList(o) {
	var textObj = $(o).parents('.edit-box').find('#content');
    var x = y = 0;
    x = $(o).offset().left;
    y = $(o).offset().top;
    $('#show01').appendTo('body');
    $('#show01').css("left",(x - 50)+"px");
    $('#show01').css("top",(y + 35)+"px")
    $('#show01').css("z-index","100001")
    
	$.ajax({
		url: '/m/comment/faceList',
		type: "get",
		data: {},
		dataType: "html",
        success: function(html, textStatus, jqXHR) {
        	$(".liaoba-popup").show('slow');
        	$("#face_list").html(html);
    		$('#face_list').find('li img').each(function(){
    			$(this).click(function(){
    				var title = this.title;
    				insertAtCursor(textObj[0],title);
    			})
    		});
        },
        error: function() {
        	showMessage('获取数据失败');
        }
	});
}

/**
 * textarea在光标处插入表情fn
 * add by bzhang
 */
function insertAtCursor(myField, myValue) 
{
	//IE support
	if (document.selection) 
	{
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
		sel.select();
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') 
	{
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		// save scrollTop before insert
		var restoreTop = myField.scrollTop;
		myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
		if (restoreTop > 0)
		{
			// restore previous scrollTop
			myField.scrollTop = restoreTop;
		}
		myField.focus();
		myField.selectionStart = startPos + myValue.length;
		myField.selectionEnd = startPos + myValue.length;
	} else {
		myField.value += myValue;
		myField.focus();
	}
}
