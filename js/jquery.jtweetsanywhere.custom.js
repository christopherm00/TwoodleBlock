$(document).ready(function() { 
	var names = $('#twittername').val().split(',');
	
	// Peform the first check to see if Twitter is up
	checkTwitter();

	// Check if you can make a Twitter API Call
	function checkTwitter() {
		// Checks if the Twitter API is up
		$.ajax({
			url: 'http://api.twitter.com/1/help/test.json',
			dataType: 'jsonp',
			// If API call is successfull then init the jTweetsAnywhere
			success: function() {
				initTweets();
			},
			// If API call fails it means Twitter is down
			failure: function() {
				$('#tweets').html('<p>Twitter is currently experiencing technical difficulties. Please try again later.</p>');
				// Recursive to check if Twitter is up again after 90 seconds
				setTimeout(checkTwitter(), 90000);
			}
		}); 
	}
	
	// Inits the jTweetsAnywhere plugin to display x amount of tweets from the specified users
	function initTweets() {
		var rl;
		var time;
		
		// Checks if you have met your ratelimit prior to calling jTweetsAnywhere which checks inside itself after every call
		$.ajax({
			url: 'http://api.twitter.com/1/account/rate_limit_status.json',
			dataType: 'jsonp',
			success: function(data) {
				rl = data.remaining_hits;
				time = calcTime(new Date().getTimezoneOffset() / 60, new Date(data.reset_time));
				
				// init jtweetsanywhere if requests left to use
				if(rl > 1) {
					$('#tweets').jTweetsAnywhere({
						username: names,
						count: $('#tweetcount').val(),
						showTweetFeed: {
							showTimeStamp: {
								refreshInterval: 15
							},
							autorefresh: {
								mode: 'trigger-insert',
								interval: 90
							}
						}
					});
				} else {
					// If no hits left alert user that they need to wait
					$('#tweets').html('<p>Twitter has reached its limit, you must wait til '+ time +' to receive more tweets.</p>');
				}
			}
		}); 
	}
	
	/*
	 * Converts a UTC date to specified timezone offset
	 * 
	 * @offset Offset from UTC time (Calculate current offset by doing new Date().getTimezoneOffset()/60)
	 * @date The date you want to convert from UTC time to specified timezone
	 */
	function calcTime(offset, date) {
		var d = date;
		
		utc = d.getTime() + (d.getTimezoneOffset() * 60000);
		
		nd = new Date(utc + (3600000 * offset));
		
		return nd.toLocaleString();
	}
});