$(document).ready(function() { 
	$('#tweets').jTweetsAnywhere({
		username: $('#twittername').val(),
		count: $('#tweetcount').val(),
		showTweetFeed: {
			showTimeStamp: {
				refreshInterval: 15
			},
			autorefresh: {
				mode: 'trigger-insert',
				interval: 30
			}
		}
	});
});
