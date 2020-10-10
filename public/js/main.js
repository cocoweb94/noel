jQuery.parseJSON = function( data ) {
	if ( !data || typeof data !== "string") {
		return null;
	}

	// Make sure leading/trailing whitespace is removed (IE can't handle it)
	data = jQuery.trim( data );

	// Attempt to parse using the native JSON parser first
	if ( window.JSON && window.JSON.parse ) {
		return window.JSON.parse( data );
	}

	// Make sure the incoming data is actual JSON
	// Logic borrowed from http://json.org/json2.js
	if ( rvalidchars.test( data.replace( rvalidescape, "@" )
			.replace( rvalidtokens, "]" )
			.replace( rvalidbraces, "")) ) {

		return ( new Function( "return " + data ) )();

	}
	jQuery.error( "Invalid JSON: " + data );
}

$(document).ready(function() {
	$(".dropdown img.flag").addClass("flagvisibility");

	$(".dropdown dt a").click(function() {
		$(".dropdown dd ul").toggle();
	});

	$(".dropdown dd ul li a").click(function() {
		var text = $(this).html();
		$(".dropdown dt a span").html(text);
		$(".dropdown dd ul").hide();
		$("#result").html("Selected value is: " + getSelectedValue("sample"));
	});

	$(".buttons .cart").click(function() {
		var id = $(this).data("id");

		var tabCommande = $.parseJSON($("#commande").val());
		if(id in tabCommande){
			tabCommande[id] = tabCommande[id] + 1;
		} else{
			tabCommande[id] = 1;
		}

		$("#commande").prop('value', JSON.stringify(tabCommande));
		setCookie(JSON.stringify(tabCommande));
		alert(document.cookie);
		$.ajax({
			url: "/addpanier",
			type: "POST",
			dataType: "text",
			contentType: "application/json",
			data: JSON.stringify(tabCommande),
			success:function(result){
				$( "#panier").html("");
				$("#panier").html(result);
			},
			error:function(xhr,status,error){
				console.log(status);
			}
		});

		alert($("#commande").val());
		alert(getCookie("commande"));
	});

	function setCookie(cvalue) {
		var d = new Date();
		d.setTime(d.getTime() + (1*24*60*60*1000));
		var expires = "expires="+d.toUTCString();

		// r�gle le pb des caract�res interdits
		if ('btoa' in window) {
			cvalue = btoa(cvalue);
		}

		alert('toto');
		alert(cvalue);

		document.cookie = "commande=" + cvalue + "; " + expires+';path=/';
	}

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');

		for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c[0] == ' ') {
				c = c.substring(1);
			}

			if (c.indexOf(name) != -1) {
				if ('btoa' in window) return atob(c.substring(name.length,c.length));}
			else return c.substring(name.length,c.length);
		}
		return false;
	}

	function getSelectedValue(id) {
		return $("#" + id).find("dt a span.value").html();
	}

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("dropdown"))
			$(".dropdown dd ul").hide();
	});


	$("#flagSwitcher").click(function() {
		$(".dropdown img.flag").toggleClass("flagvisibility");
	});
});