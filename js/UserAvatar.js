// http://www.mediawiki.org/wiki/JQuery

$(document).ready(function() {

	// Way to get jQuery version
	console.log($().jquery);
	
	// L10n possible here!
	console.log("UserAvatar extension is loaded!");

});

// On click on Avatar profile
$(document).on("click", ".useravatar-profile > img", function() {

	console.log("Clicked!");
	var username = $(this).attr('data-username');

	$.get( mw.util.wikiScript(), {
		format: 'json',
		action: 'ajax',
		rs: 'UserAvatar::getUserInfo',
		rsargs: [username] // becomes &rsargs[]=arg1&rsargs[]=arg2...
	}, function(data) {
		alert(data);
	});

});
