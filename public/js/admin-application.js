/* <![CDATA[ */
$(document).ready(function(){
	$('#cropbox').Jcrop({
		aspectRatio: 1,
		onSelect: updateCoords
	});
	
	$("ul#thumbs").sortable({
		deactivate: function(){
		var id = $("input[name=post]").val();
		var i = new Array();
		var a = 0;
			$("ul#thumbs li a.delete").each( function(){
				i[a] = $(this).attr("rel");
				a++;
			});
		$.ajax({
			type: "POST",
			url: "/wp-content/plugins/portfolio/lib/ajax.php",
			data: "action=order&parent_id=" + id + "&menu_order=" + i,
			success: function(result){
				alert(result);
			}
		});
		}
	});

	$("a.delete").click( function(){
		var rel = $(this).attr("rel");
		$.ajax({
		type: "POST",
		url: "/wp-content/plugins/portfolio/lib/ajax.php",
		data: "action=delete&attach_id=" + rel,
		success: function(result){
			if(result){
				$("a[rel=" + rel + "]").parents("li").fadeOut();
			}
		}
		});
	});
	
	$("a.primary").click( function(){
		var rel = $(this).attr("rel");
		$.ajax({
		type: "POST",
		url: "/wp-content/plugins/portfolio/lib/ajax.php",
		data: "action=primary&attach_id=" + rel,
		success: function(result){
			if(result){
				window.location.reload();
			}
		}
		});
	});
	
	$("span.show").toggle( 
		function(){ $(this).next().slideDown();},
		function(){ $(this).next().slideUp();}
	);
});

function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
};

function checkCoords()
{
	if (parseInt($('#w').val())) return true;
	alert('Please select a crop region then press submit.');
	return false;
};
/* ]]>*/

