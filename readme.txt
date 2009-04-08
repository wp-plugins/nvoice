=== NVoice ===
Contributors: nTechnology
Donate link: http://www.ntechnology.pl/donate
Tags: voice, text-to-speech, text to speech, nvoice
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: 1.0

Your post's in audio - The easiest way to create speech recordings.

== Description ==

Your post's in audio - The easiest way to create speech recordings.
	
== Installation ==

	- Upload into /wp-content/plugins/
	- Active Plugin on Your blog.
	- Go up on NVoice Settings Page.
	- Create free account on: http://www.ivona.com/online/
	- Enter account details on settings page and save.

	- Insert this code in template where do you want to see a player:

<?php if(get_post_meta($post->ID, "nvoice", true) != "") { ?>
          <p>
          <div id="flashplayer"></div><script type="text/javascript">var flashvars = {}; var d = new Date(); flashvars.source="<?=get_post_meta($post->ID, "nvoice", true); ?>"; flashvars.configURL= "http://static.ivona.com/online/static/xml/config.xml?timestamp="+d.getTime(); var saJsHost = (("https:" == document.location.protocol) ? "https://secure.ivona.com/online/static/" : "http://static.ivona.com/online/static/"); document.write(unescape("%3Cscript src='" + saJsHost  + "js/saPlayer.js?timestamp=" + d.getTime()+ "type='text/javascript'%3E%3C/script%3E"));</script>
          </p>
<?php } ?>
