<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<script src="/js/audiojs/audio.min.js"></script>
</head>
<body>

<audio src="https://localhost/media/audio/oud-cursisten-compilatie-2014.mp3"></audio>

<script>

audiojs.events.ready(function() {
    var as = audiojs.createAll();
  });

</script>

</body>
</html>