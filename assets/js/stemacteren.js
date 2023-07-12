///////////////////////
// Online of offline //
///////////////////////

var localhost = document.URL.substr(7, 5);
var online = true;
if(localhost == 'local') online = false;

var archief;
var groepen;
var verzonden;
var prijs;
var Mastercard_prijs;
var Mastercard_percentage_prijs;

//////////////////
// Audio Player //
//////////////////

function initAudio()
{
	audiojs.events.ready(function()
	{
		var audio = audiojs.createAll();
	});
}

function showDocentfields(el) {
	if(document.getElementById('opleidingsmedewerker')) {
		var div = document.getElementById('opleidingsmedewerker');

		if(el == 'opleidingsmedewerker') {
            div.style.display = "block";
		} else {
            div.style.display = "none"
		}
	}
}

var nav_open = 0;

  function openNav() {
	  if (nav_open == 0) {
		document.getElementById("mySidenav").style.width = "350px";
		nav_open = 1;
	  } else {
		document.getElementById("mySidenav").style.width = "0";
		nav_open = 0;
	  }
  }

///////////////
// Aanmelden //
///////////////

function initAanmelden()
{
	$('#aanmelden_formulier_kort').change(function(){ toonFormulier('kort'); });
	$('#aanmelden_formulier_lang').change(function(){ toonFormulier('lang'); });
}

function toonFormulier(lengte)
{
	if(lengte == 'kort')
	{
		console.log('kort');
		$('#formulier_kort').css('display', 'block');
		$('#formulier_lang').css('display', 'none');
	}
	else
	{
		console.log('lang');
		$('#formulier_kort').css('display', 'none');
		$('#formulier_lang').css('display', 'block');
	}
}

function stemtestCodeToggle(radio) {
	if(radio == 'Ja')
	{
		$('#aanmelden_kort_code').css('display', 'block');
		$('#stemtest_code_label').css('display', 'block');
	}
	else
	{
		$('#aanmelden_kort_code').css('display', 'none');
		$('#stemtest_code_label').css('display', 'none');
	}
}



//////////////
// Docenten //
//////////////

var docentActive;

function initDocenten()
{
	$('#docenten #biografieen article:not(:first-child)').css('display', 'none');
	$('#docenten #namen ul li:first-child a').addClass('active');

	docentActive = $('#docenten #namen ul li:first-child a').attr('href').substr(1);

	$('#docenten #namen ul li a').click(function(e){

		e.preventDefault();

		var docentClicked = $(this).attr('href').substr(1);

		if(docentClicked != docentActive)
		{
			// Naam van docent selecteren

			$('#docenten #namen ul li a').removeClass('active');
			$(this).addClass('active');

			// Biografie van docent tonen

			$('#docenten #biografieen #' + docentActive).fadeOut(250, function(){
				$('#docenten #biografieen #' + docentClicked).fadeIn(250);
				docentActive = docentClicked;
				document.getElementById('docenten').scrollIntoView();
			});
		}
	});
}

///////////////////
// Audio opnemen //
///////////////////

var audio_context;
var recorder;

function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);
    recorder = new Recorder(input, {numChannels:1});
}

function startRecording(button) {
    recorder && recorder.record();
	var img = document.createElement('img');
	var base_url 		= 'https://localhost/';

    button.disabled = true;
    button.nextElementSibling.disabled = false;
	recordingslist.innerHTML = '';
	img.src = base_url + '/assets/images/loader.gif';
	img.width = 30;
	recordingslist.appendChild(img);
}

function stopRecording(button) {
    recorder && recorder.stop();
    button.disabled = true;
    button.previousElementSibling.disabled = false;

    createDownloadLink();

	if(recorder != undefined) {
		recorder.clear();
	} else {
		alert('Kan uw audio niet opnemen. Heeft u de microfoon voor deze website geblokkeerd?');
	}
}

function stopWAVRecording(button) {
    recorder && recorder.stop();
    button.disabled = true;
    button.previousElementSibling.disabled = false;

    createWAVDownloadLink();

	if(recorder != undefined) {
		recorder.clear();
	} else {
		alert('Kan uw audio niet opnemen. Heeft u de microfoon voor deze website geblokkeerd?');
	}
}

 function imagePreview(){
	var number = window.location.href.split("deelnemers/");
	if(window.location.href.includes('deelnemers/') && number[1] !== "") {
		xOffset = 15;
		yOffset = 30;
	} else {
		xOffset = 15;
		yOffset = -220;
	}

		var Mx = $(document).width();
		var My = $(document).height();

	var callback = function(event) {
		var $img = $("#preview");

		var trc_x = xOffset + $img.width();
		var trc_y = yOffset + $img.height();

		trc_x = Math.min(trc_x + event.pageX, Mx);
		trc_y = Math.min(trc_y + event.pageY, My);

		$img
			.css("top", (trc_y - $img.height()) + "px")
			.css("left", (trc_x - $img.width())+ "px");
	};

	$("a.preview").hover(function(e){
			Mx = $(document).width();
			My = $(document).height();

			$("body").append("<p id='preview'><img src='"+ this.href +"' alt='Image preview' style='width:200px; height:200px' /></p>");
			callback(e);
			$("#preview").fadeIn("fast");
		},
		function(){
			this.title = this.t;
			$("#preview").remove();
		}
	)
	.mousemove(callback);
};

function showBruikleen() {
	let buycheck = document.getElementById('product-117')
	buycheck.checked = false;

	if(document.getElementsByName('huur_producten[]')) {
		checkboxes = document.getElementsByName('huur_producten[]')
		geselecteerd = false;

        for (var i = 0, n = checkboxes.length; i < n; i++) {
			if(checkboxes[i].checked == true) {
				geselecteerd = true;
			}
        }

        if (geselecteerd) {
			container = document.getElementsByClassName('akkoord_container');
			for (i = 0; i < container.length; i++) {
				container[i].style.display = "block";
			}

        } else {
			container = document.getElementsByClassName('akkoord_container');
			for (i = 0; i < container.length; i++) {
				container[i].style.display = "none";
			}
        }
    }
}
//remove the check huur 
function removecheckhuur() {
	if(document.getElementsByName('huur_producten[]')) {
		let huurcheck = document.getElementById('producthuur-117')
		huurcheck.checked = false;
		geselecteerd = false;
        if (geselecteerd) {
			container = document.getElementsByClassName('akkoord_container');
			for (i = 0; i < container.length; i++) {
				container[i].style.display = "block";
			}

        } else {
			container = document.getElementsByClassName('akkoord_container');
			for (i = 0; i < container.length; i++) {
				container[i].style.display = "none";
			}
        }
    }
}

function createDownloadLink() {
    recorder && recorder.exportWAV(function(blob) {

        var url = URL.createObjectURL(blob);
        var au = document.createElement('audio');
		var hf = document.createElement('a');
		var button = document.getElementById('button_opnemen');

        au.controls = true;
        au.src = url;
        hf.href = url;
        hf.download =  new Date().toISOString() + '.mp3';
        hf.innerHTML = '<p>'+ hf.download + '</p>';
		recordingslist.innerHTML = '';
		recordingslist.appendChild(au);
		recordingslist.appendChild(hf);
		button.innerText = 'Opnieuw opnemen';

		uploadAudio(blob);
});
}

function createWAVDownloadLink() {
    recorder && recorder.exportWAV(function(blob) {

        var url = URL.createObjectURL(blob);
        var au = document.createElement('audio');
		var hf = document.createElement('a');
		var button = document.getElementById('button_opnemen');

        au.controls = true;
        au.src = url;
        hf.href = url;
        hf.download =  new Date().toISOString() + '.wav';
        hf.innerHTML = '<p>'+ hf.download + '</p>';
		recordingslist.innerHTML = '';
		recordingslist.appendChild(au);
		recordingslist.appendChild(hf);
		button.innerText = 'Opnieuw opnemen';

		uploadWAVAudio(blob);
});
}

function uploadAudio(blob) {
		var xhr=new XMLHttpRequest();
		xhr.onload=function(e) {
			if(this.readyState === 4) {
				alert(JSON.parse(xhr.responseText));
			}
		};

		var fd = new FormData();
		fd.append('file', blob);
		fd.append('ID', document.getElementById('ID').value);

		xhr.open("POST","/ajax/uploadAudio",true);
		xhr.send(fd);
}

function uploadWAVAudio(blob) {
	var xhr=new XMLHttpRequest();
	xhr.onload=function(e) {
		if(this.readyState === 4) {
			alert(JSON.parse(xhr.responseText));
		}
	};

	var fd = new FormData();
	fd.append('file', blob);
	fd.append('ID', document.getElementById('ID').value);

	xhr.open("POST","/ajax/uploadWAVAudio",true);
	xhr.send(fd);
}

window.onload = function init() {
	var recordinglijst = document.getElementById('recordingslist');
	if(recordinglijst) {
		try {
			window.AudioContext = window.AudioContext || window.webkitAudioContext;
			navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
			window.URL = window.URL || window.webkitURL;

			audio_context = new AudioContext;
		} catch (e) {
			alert('Audio opnemen wordt ondersteumd in uw browser!');
		}

		navigator.getUserMedia({audio: true}, startUserMedia, function (e) {
		});
	}
};


////////////
// Vragen //
////////////

function scrollen(vraag)
{
	$("body, html").animate({ scrollTop: $('#' + vraag).offset().top }, 500, function(){
		$('#' + vraag).fadeTo(250, 0.25, function(){
			$('#' + vraag).fadeTo(250, 1);
		});
	});
}

function initVragen()
{
	// Check hashtag

	var hashtag = document.location.hash.substring(1);
	if(hashtag != '') scrollen('vraag' + hashtag);

	$('#vragen ol li a').click(function(e)
	{
		e.preventDefault();
		var vraag = $(this).attr('href').substr(1);
		scrollen(vraag);
	});
}

var keuze = '';

function initKeuzes()
{
	keuze = $('#keuzes input:checked').val();

	$('#keuzes input').change(function(e){
		$('.keuze.' + keuze).removeClass('active');
		keuze = $(this).val();
		$('.keuze.' + keuze).addClass('active');
	});
}

function openArchief(evt, Name) {
	// Declare all variables
	var i, tabcontent, tablinks;

	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}

	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}

	// Show the current tab, and add an "active" class to the link that opened the tab
	document.getElementById(Name).style.display = "block";

	if(Name == 'archief') {
		archief = true;
		groepen = false;
		aanmelden = false;
		document.getElementById('archief_open').val = true;
		if(document.getElementById('tab_groepen')) {
			document.getElementById('groepen_open').val = false;
			}
	} else if(Name == 'groepen') {
		archief = false;
		groepen = true;
		document.getElementById('archief_open').val = false;
		if(document.getElementById('tab_groepen')) {
			document.getElementById('groepen_open').val = true;
			}
	} else if(Name == 'aanmelden') {
		archief = false;
		groepen = false;
		aanmelden = true;
		document.getElementById('archief_open').val = false;
		if(document.getElementById('tab_aanmelden')) {
			document.getElementById('tab_aanmelden').val = true;
		}
	} else {
		archief = false;
		groepen = false;
		aanmelden = false;
		document.getElementById('archief_open').val = false;
		if(document.getElementById('tab_groepen')) {
			document.getElementById('groepen_open').val = false;
			}
	}

	evt.currentTarget.className += " active";
}

function openInbox(evt, Name) {
	var i, tabcontent, tablinks;

	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}

	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}

	document.getElementById(Name).style.display = "block";

	if(Name == 'verzonden') {
		verzonden = true;
		document.getElementById('verzonden_open').val = true;
	} else {
		verzonden = false;
		document.getElementById('verzonden_open').val = false;
	}

	evt.currentTarget.className += " active";
}

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#profiel_foto')
				.attr('src', e.target.result)
				.width(150)
				.height(200);

			$('#profiel_foto_a').attr('href', e.target.result)
		};

		reader.readAsDataURL(input.files[0]);
	}
}


//////////////////////
// Media selecteren //
//////////////////////

var mediabibliotheekOpen = false;
var mediaAantal = 0;
var mediaGeselecteerd = [];
var mediaGesorteerd = '';
var mediaSoort = 'media';

function openMediabibliotheek(soort, welkomstmail, specific_sort)
{
	initMediabibliotheek(soort, welkomstmail, specific_sort);

	if(!mediabibliotheekOpen)
	{
		// OUDE MEDIA VERWIJDEREN

		$('#media .media').remove();

		// MEDIA OF UITGELICHTE AFBEELDING BIJWERKEN?

		if (soort != null) {
			mediaSoort = soort;
		}

		// BIBLIOTHEEK OPENEN

		mediabibliotheekOpen = true;
		$('#lightbox').css('display', 'block');
		$('#mediabibliotheek').css('display', 'block');


		// HUIDIGE MEDIA OPHALEN

		var item_media = $('#item_' + mediaSoort).val();
		item_media = item_media.split(',');
		mediaGeselecteerd = [];
		for(var i = 0; i < item_media.length; i++){ if(item_media[i] != '') mediaGeselecteerd[i] = item_media[i]; }


		// MEDIA OPHALEN EN WEERGEVEN

		$.get('/ajax/media', function(data){
			if(data.length > 0)
			{
				for(var i = 0; i < data.length; i++)
				{
					var media_ID 		= data[i]['media_ID'];
					var media_type 		= data[i]['media_type'];
					var media_src 		= data[i]['media_src'];
					var media_titel 	= data[i]['media_titel'];

					if(media_type == 'afbeelding') media_thumbnail = '/media/afbeeldingen/thumbnail/' + media_src;
					else media_thumbnail = '//view.vzaar.com/' + media_src + '/image';

					var active = '';
					if($.inArray(media_ID, mediaGeselecteerd) >= 0){ active = 'active'; }

					if(media_type == 'pdf')
					{
						$('#media').append('<div class="media ' + media_type + ' ' + active + '" data-media="' + media_ID + '" data-type="' + media_type + '" data-src="' + media_src + '" data-titel="' + media_titel + '" title="PDF: ' + media_titel + '"><p>' + media_titel + '</p></div>');
					}
					else if(media_type == 'afbeelding')
					{
						$('#media').append('<div class="media ' + media_type + ' ' + active + '" data-media="' + media_ID + '" data-type="' + media_type + '" data-src="' + media_src + '" data-titel="' + media_titel + '"><p><img src="' + media_thumbnail + '" alt="Afbeelding: ' + media_titel + '" title="Afbeelding: ' + media_titel + '" /></p></div>');
					}
					else if(media_type == 'video' && welkomstmail == undefined)
					{
						$('#media').append('<div class="media ' + media_type + ' ' + active + '" data-media="' + media_ID + '" data-type="' + media_type + '" data-src="' + media_src + '" data-titel="' + media_titel + '"><p><img src="' + media_thumbnail + '" alt="Video: ' + media_titel + '" title="Video: ' + media_titel + '" /></p></div>');
					}
					else if(media_type == 'playlist' && welkomstmail == undefined)
					{
						$('#media').append('<div class="media ' + media_type + ' ' + active + '" data-media="' + media_ID + '" data-type="' + media_type + '" data-src="' + media_src + '" data-titel="' + media_titel + '" title="Playlist: ' + media_titel + '"><p>' + media_titel + '</p></div>');
					}
					else if(media_type == 'mp3')
					{
						$('#media').append('<div class="media ' + media_type + ' ' + active + '" data-media="' + media_ID + '" data-type="' + media_type + '" data-src="' + media_src + '" data-titel="' + media_titel + '" title="MP3: ' + media_titel + '"><p>' + media_titel + '</p></div>');
					}
				}

				if(specific_sort == "afbeelding") {
					$('#media .afbeelding').css('display', 'block');
					$('#media .media:not(.afbeelding)').css('display', 'none');
				}

				$('#media .media').click(function(e)
				{
					e.preventDefault();

					if(mediaAantal == 1)
					{
						if($(this).hasClass('active'))
						{
							$(this).removeClass('active');
						}
						else
						{
							$('#media .media.active').removeClass('active');
							$(this).addClass('active');
						}
					}
					else
					{
						if(!$(this).hasClass('active'))
						{
							$(this).addClass('active');
						}
						else
						{
							$(this).removeClass('active');
						}
					}
				});
			}
		}, 'json');
	}
}

function sorteren(e)
{
	e.preventDefault();

	var media_type = $(this).data('type');

	if(mediaGesorteerd == media_type)
	{
		$(this).removeClass('active');

		$('#media .media').css('display', 'block');

		mediaGesorteerd = '';
	}
	else
	{
		$('#mediabibliotheek #header p a.active').removeClass('active');
		$(this).addClass('active');

		$('#media .' + media_type).css('display', 'block');
		$('#media .media:not(.' + media_type + ')').css('display', 'none');

		mediaGesorteerd = media_type;
	}
}

function ontkoppelen(media) {
	if(media) {
		media = media.closest('tr');

		if($('#media_lijst').length > 0)
		{
			media.remove();
			hidden_media_ID_volgorde_bijwerken();
		}
	} else {
		$('#' + mediaSoort + ' #media_lijst table tr').remove();
		$('#item_' + mediaSoort).val('');
	}
}

function koppelen()
{
	var media_ID_gebruiken = false;

	if ($('#' + mediaSoort + ' #media_lijst table').hasClass('js-sorteren-alleen-media-bijwerken')) {
		media_ID_gebruiken = true;
	}

	$('#' + mediaSoort + ' #media_lijst table tr').remove();

	if($('.media.active').length > 0)
	{
		var geselecteerd = '';

		$('.media.active').each(function()
		{
			var media_ID 		= $(this).data('media');
			var media_type 		= $(this).data('type');
			var media_src 		= $(this).data('src');
			var media_titel		= $(this).data('titel');
			var media_link 		= '#';
			var base_url 		= 'https://localhost/';
			var blank           = '_blank';

			if(parseInt(media_ID) > 0)
			{
				geselecteerd = geselecteerd + media_ID + ',';

				if(media_type == 'pdf') { media_thumbnail = '/assets/images/pdf.png'; media_link = base_url + '/media/pdf/' + media_src; }
				else if(media_type == 'afbeelding') { media_thumbnail = '/media/afbeeldingen/thumbnail/' + media_src; media_link = base_url + '/media/afbeeldingen/origineel/' + media_src; }
				else if(media_type == 'video') { media_thumbnail = '//view.vzaar.com/' + media_src + '/image'; media_link = '//view.vzaar.com/' + media_src; }
				else if(media_type == 'playlist') { media_thumbnail = '/assets/images/playlist.png'; media_link = "#"; }
				else if(media_type == 'mp3') { media_thumbnail = '/assets/images/mp3.png'; media_link = base_url + '/media/audio/' + media_src; }

				if(media_link != '#') { blank = '_black'; } else { blank = ''; }

				if (media_ID_gebruiken) {
					$('#' + mediaSoort + ' #media_lijst table').append('<tr data-media-id="' + media_ID + '"><td class="media_image"><a href="' + media_link + '" target="' + blank + '"><img src="' + media_thumbnail + '" title="' + media_titel + '" /></a></td><td class="media_titel">' + media_titel + '</td><td class="betaald" onclick="ontkoppelen(this);"><span style="color:red; cursor:pointer;">X</span></td></tr>');
				} else {
					$('#' + mediaSoort + ' #media_lijst table').append('<tr><td class="media_image"><a href="' + media_link + '" target="' + blank + '"><img src="' + media_thumbnail + '" title="' + media_titel + '" /></a></td><td class="media_titel">' + media_titel + '</td><td class="betaald" onclick="ontkoppelen(this);"><span style="color:red; cursor:pointer;">X</span></td></tr>');
				}
			}
		});

		$('#item_' + mediaSoort).val(geselecteerd);
	}
	else
	{
		$('#item_' + mediaSoort).val('');
	}

	sluitMediabibliotheek();
}

function sluitMediabibliotheek()
{
	if(mediabibliotheekOpen)
	{
		mediabibliotheekOpen = false;
		$('#lightbox').css('display', 'none');
		$('#mediabibliotheek').css('display', 'none');
	}
}

function sluitModal()
{
	var modal = document.getElementById('myModal');

	if(modal) {
		modal.style.display = "none";
	}
}

function initMediabibliotheek(soort, welkomstmail, specific_sort)
{
	// INIT MEDIABIBLIOTHEEK

	mediaAantal = $('#item_' + soort).data('aantal');

	$('#inhoud').prepend('<div id="lightbox"><div id="mediabibliotheek"><div id="header"><div id="button">Koppelen</div><h1>Media koppelen</h1><p></p></div><div id="container"><div id="media"></div></div></div></div>');
	if(mediaAantal == 1) $('#mediabibliotheek #header p').text('Selecteer één item en klik op de button "Koppelen". ');
	else $('#mediabibliotheek #header p').text('Selecteer één of meerdere items en klik op de button "Koppelen". ');

	if(specific_sort == "afbeelding") {
		$('#mediabibliotheek #header p').append('');
	} else {
		if(welkomstmail == undefined) {
			$('#mediabibliotheek #header p').append('<strong>Sorteren:</strong> <a class="sorteren" data-type="pdf">PDF</a> | <a class="sorteren" data-type="afbeelding">Afbeelding</a> | <a class="sorteren" data-type="video">Video</a> | <a class="sorteren" data-type="playlist">Playlist</a> | <a class="sorteren" data-type="mp3">MP3</a> | <a class="sorteren" data-type="wav">WAV</a>.');
		}

		if(welkomstmail == true) {
			$('#mediabibliotheek #header p').append('<strong>Sorteren:</strong> <a class="sorteren" data-type="pdf">PDF</a> | <a class="sorteren" data-type="afbeelding">Afbeelding</a> | <a class="sorteren" data-type="mp3">MP3</a> | <a class="sorteren" data-type="wav">WAV</a>.');
		}
	}

	$('#mediabibliotheek #header p a.sorteren').click(sorteren);

	$('#mediabibliotheek #button').click(function(e){ e.preventDefault(); koppelen(); });
}



//////////////
// Sortable //
//////////////

function sorteerRijen()
{
	var js_sorteren = $('.js-sorteren tbody');
	var js_sorteren_alleen_media = $('.js-sorteren-alleen-media-bijwerken tbody');

	if(js_sorteren.length > 0) {
		js_sorteren.sortable({ helper: fixWidth, stop: positiesUpdaten });
	}

	if(js_sorteren_alleen_media.length > 0) {
		js_sorteren_alleen_media.sortable({ helper: fixWidth, stop: hidden_media_ID_volgorde_bijwerken });
	}
}

function fixWidth(e, ui)
{
	ui.children().each(function()
	{
		$(this).width($(this).width());
	});

	return ui;
}

function positiesUpdaten(e, ui)
{
	var items = $('.js-sorteren').data('items');


	if(items != undefined) {

		var update_items = new Array();

		$('.js-sorteren tbody tr').each(function(index){
			var item_ID = $(this).data('item');
			var item_positie = index + 1;
			update_items[index] = new Array(item_ID, item_positie);
		});


		$.post('/cms/update/posities', { items: items, update: update_items });
	}
}

function hidden_media_ID_volgorde_bijwerken()
{
	var media_IDs = "";

	$('.js-sorteren-alleen-media-bijwerken tbody tr').each(function(index){
		var media_ID = $(this).data('media-id');

		if(!isNaN(media_ID)) {
			media_IDs = media_IDs + media_ID + ',';
		}
	});

	// Hidden veld met media_ID's bijwerken
	if(media_IDs.length > 0) {
		$('#item_media').val(media_IDs);
	} else {
		$('#item_media').val('');
	}
}

function upload_profile_image() {
	var xhr, formData;

	xhr = new XMLHttpRequest();
	xhr.withCredentials = true;
	xhr.open('POST', base_url + '/ajax/uploadprofielimage');

	xhr.onload = function() {
		var json;

		if (xhr.status != 200) {
			failure('HTTP Error: ' + xhr.status);
			return;
		}

		json = JSON.parse(xhr.responseText);

		if (!json || typeof json.location != 'string') {
			failure('Invalid JSON: ' + xhr.responseText);
			return;
		}

		success(json.location);
	};

	formData = new FormData();
	formData.append('file', blobInfo.blob(), blobInfo.filename());

	xhr.send(formData);
}

/////////////
// TinyMCE //
/////////////

function initTinyMCE()
{
	var taal_url = 'https://localhost/assets/js/tinymce/langs/nl.js';
	if(!online) taal_url = 'http://localhost:8888/localhost/assets/js/tinymce/langs/nl.js';

	var tinymce_css = "https://localhost/assets/css/tinymce.css?" + new Date().getTime();
	if(!online) tinymce_css = "http://localhost:8888/localhost/assets/css/tinymce.css?" + new Date().getTime();

	var base_url = '/';

	tinymce.init({
		selector: 'textarea.opmaak',
		height: 500,
		file_browser_callback_types: 'file image media',
		image_advtab: true,
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table paste imagetools"
		],
		relative_urls: false,
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | media | link image",
		imagetools_cors_hosts: ['www.tinymce.com', 'codepen.io'],
		content_css: [
			'https://localhost..nl/assets/css/tinymce.css'

		],
		image_title: true,
		// enable automatic uploads of images represented by blob or data URIs
		automatic_uploads: true,
		media_live_embeds: true,
		images_reuse_filename: true,
		images_upload_credentials: true,
		// URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
		images_upload_url: base_url + 'ajax/uploadimage', // upload function

		file_picker_types: 'image',
		images_upload_base_path: '/www/s/t/e/localhost/public_html/media/uploads/',

		file_picker_callback: function(cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');

			input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.readAsDataURL(file);

				reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    cb(blobInfo.blobUri(), { title: file.name });
			    };
            };

			input.click();
		},

		images_upload_handler: function (blobInfo, success, failure) {
			var xhr, formData;

			xhr = new XMLHttpRequest();
			xhr.withCredentials = true;
			xhr.open('POST', base_url + 'ajax/uploadimage');

			xhr.onload = function() {
				var json;

				if (xhr.status != 200) {
					failure('HTTP Error: ' + xhr.status);
					return;
				}

				json = JSON.parse(xhr.responseText);

				if (!json || typeof json.location != 'string') {
					failure('Invalid JSON: ' + xhr.responseText);
					return;
				}

				success(json.location);
			};

			formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());

			xhr.send(formData);
		}
	});

	tinymce.init({
		selector: 'textarea.opmaak_simpel',
		height: 500,
		menubar: false,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table paste code'
		],
		toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	});
}



/////////////////////
// URL's genereren //
/////////////////////

function getValidURL(url)
{
	// Omzetten naar kleine letters

	url = url.toLowerCase();


	// Spaties verwijderen en vervangen voor streepje

	url = url.trim();
	url = url.replace(/ +(?= )/g, '');
	url = url.replace(/ /g, '-');


	// Speciale letters veranderen

	url = url.replace(/ä/g, 'a');
	url = url.replace(/á/g, 'a');
	url = url.replace(/à/g, 'a');
	url = url.replace(/â/g, 'a');
	url = url.replace(/ë/g, 'e');
	url = url.replace(/é/g, 'e');
	url = url.replace(/è/g, 'e');
	url = url.replace(/ê/g, 'e');
	url = url.replace(/ï/g, 'i');
	url = url.replace(/í/g, 'i');
	url = url.replace(/ì/g, 'i');
	url = url.replace(/î/g, 'i');
	url = url.replace(/ö/g, 'o');
	url = url.replace(/ó/g, 'o');
	url = url.replace(/ò/g, 'o');
	url = url.replace(/ô/g, 'o');
	url = url.replace(/ü/g, 'u');
	url = url.replace(/ú/g, 'u');
	url = url.replace(/ù/g, 'u');
	url = url.replace(/û/g, 'u');
	url = url.replace(/ç/g, 'c');


	// Speciale tekens veranderen / verwijderen

	url = url.replace(/±/g, '');
	url = url.replace(/§/g, '');
	url = url.replace(/!/g, '');
	url = url.replace(/@/g, '');
	url = url.replace(/#/g, '');
	url = url.replace(/\$/g, '');
	url = url.replace(/%/g, 'procent');
	url = url.replace(/\^/g, '');
	url = url.replace(/&/g, 'en');
	url = url.replace(/\*/g, '');
	url = url.replace(/\(/g, '');
	url = url.replace(/\)/g, '');
	url = url.replace(/\_/g, '-');
	url = url.replace(/\+/g, '');
	url = url.replace(/=/g, '');
	url = url.replace(/\{/g, '');
	url = url.replace(/\}/g, '');
	url = url.replace(/\[/g, '');
	url = url.replace(/\]/g, '');
	url = url.replace(/:/g, '');
	url = url.replace(/;/g, '');
	url = url.replace(/\'/g, '');
	url = url.replace(/\"/g, '');
	url = url.replace(/\|/g, '-');
	url = url.replace(/\\/g, '');
	url = url.replace(/</g, '');
	url = url.replace(/>/g, '');
	url = url.replace(/\?/g, '');
	url = url.replace(/\//g, '-');
	url = url.replace(/\./g, '-');
	url = url.replace(/,/g, '-');


	// Meerdere streepjes veranderen naar een enkel streepje

	url = url.replace(/-+/g, '-');


	// Streepjes aan het begin en aan het einde verwijderen

	var eersteKarakter = url.slice(0, 1);
	var laatsteKarakter = url.slice(-1);

	if(eersteKarakter == '-') url = url.slice(1);
	if(laatsteKarakter == '-') url = url.slice(0, -1);


	// Geldige url terugsturen

	return url;
}

var url = '';
var edit = false;
var editConfirm;

function initURL()
{
	url = $('#item_url').val();

	if(url == '') edit = true;

	$('#item_titel').keyup(function()
	{
		var titel_url = getValidURL($(this).val());
		if(edit)
		{
			$('#item_url').val(titel_url);
			url = titel_url;
		}
	});

	$('#item_titel').focusout(function()
	{
		if(!edit)
		{
			var titel_url = getValidURL($(this).val());

			if(titel_url != url)
			{
				editConfirm = confirm('De titel is aangepast, maar de URL hebben we hetzelfde gelaten i.v.m. je zoekresulaten. Wil je de URL toch wijzigen?')

				if(editConfirm)
				{
					$('#item_url').val(titel_url);
					url = titel_url;
				}
			}
		}
	});
}

////////////
// ZOEKEN //
////////////

var zoeken_term = '';

function initZoeken(docent)
{
	$('#zoeken form').submit(function(e){ e.preventDefault(); });

	$('#zoeken_term').keyup(function(e)
	{
		var term = $(this).val();

		if(term != zoeken_term)
		{
			zoeken_term = term;

			if(zoeken_term.length > 2)
			{
				$.post('/cms/update/zoeken', { zoekterm: zoeken_term }, function(data)
				{
					$('#zoekresultaten table tr').remove();
					$('#zoekresultaten').addClass('open');

					if(docent == undefined) {
						// Deelnemers tonen

						for (key in data['deelnemers']) {
							var deelnemer_ID = data['deelnemers'][key]['gebruiker_ID'];
							var deelnemer_voornaam = data['deelnemers'][key]['gebruiker_voornaam'];
							var deelnemer_tussenvoegsel = data['deelnemers'][key]['gebruiker_tussenvoegsel'];
							var deelnemer_achternaam = data['deelnemers'][key]['gebruiker_achternaam'];
							var deelnemer_email = data['deelnemers'][key]['gebruiker_emailadres'];
							var deelnemer_woonplaats = data['deelnemers'][key]['gebruiker_plaats'];
							var deelnemer_telefoonnummer = data['deelnemers'][key]['gebruiker_telefoonnummer'];
							var deelnemer_naam = deelnemer_voornaam + ' ' + deelnemer_tussenvoegsel + ' ' + deelnemer_achternaam;

							$('#zoekresultaten table').append('<tr><td class="type">Deelnemer</td><td class="type">' + deelnemer_naam + '</td><td class="type"> ' + deelnemer_email + ' </td><td class="type"> ' + deelnemer_woonplaats + ' </td><td class="type"> ' + deelnemer_telefoonnummer + '</td><td class="bekijken"><a href="https://localhost/cms/deelnemers/' + deelnemer_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/deelnemers/wijzigen/' + deelnemer_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/deelnemers/verwijderen/' + deelnemer_ID + '"></a></td></tr>');
						}

                        for (key in data['kandidaten']) {
                            var kandidaat_ID = data['kandidaten'][key]['gebruiker_ID'];
                            var kandidaat_voornaam = data['kandidaten'][key]['gebruiker_voornaam'];
                            var kandidaat_tussenvoegsel = data['kandidaten'][key]['gebruiker_tussenvoegsel'];
                            var kandidaat_achternaam = data['kandidaten'][key]['gebruiker_achternaam'];
                            var kandidaat_email = data['kandidaten'][key]['gebruiker_emailadres'];
                            var kandidaat_woonplaats = data['kandidaten'][key]['gebruiker_plaats'];
                            var kandidaat_telefoonnummer = data['kandidaten'][key]['gebruiker_telefoonnummer'];
                            var kandidaat_naam = kandidaat_voornaam + ' ' + kandidaat_tussenvoegsel + ' ' + kandidaat_achternaam;

                            $('#zoekresultaten table').append('<tr><td class="type">Kandidaat</td><td class="type">' + kandidaat_naam + '</td><td class="type"> ' + kandidaat_email + ' </td><td class="type"> ' + kandidaat_woonplaats + ' </td><td class="type"> ' + kandidaat_telefoonnummer + '</td><td class="bekijken"><a href="https://localhost/cms/deelnemers/' + kandidaat_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/deelnemers/wijzigen/' + kandidaat_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/deelnemers/verwijderen/' + kandidaat_ID + '"></a></td></tr>');
                        }

						// Beheerders tonen

						for (key in data['beheerders']) {
							var beheerder_ID = data['beheerders'][key]['gebruiker_ID'];
							var beheerder_voornaam = data['beheerders'][key]['gebruiker_voornaam'];
							var beheerder_tussenvoegsel = data['beheerders'][key]['gebruiker_tussenvoegsel'];
							var beheerder_achternaam = data['beheerders'][key]['gebruiker_achternaam'];
							var beheerder_naam = beheerder_voornaam + ' ' + beheerder_tussenvoegsel + ' ' + beheerder_achternaam;

							$('#zoekresultaten table').append('<tr><td class="type">Beheerder</td><td>' + beheerder_naam + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/beheerders/' + beheerder_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/beheerders/wijzigen/' + beheerder_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/beheerders/verwijderen/' + beheerder_ID + '"></a></td></tr>');
						}

						// Docenten tonen

						for (key in data['docenten']) {
							var docent_ID = data['docenten'][key]['docent_ID'];
							var docent_naam = data['docenten'][key]['docent_naam'];

							$('#zoekresultaten table').append('<tr><td class="type">Docent</td><td>' + docent_naam + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/docenten/' + docent_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/docenten/wijzigen/' + docent_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/docenten/verwijderen/' + docent_ID + '"></a></td></tr>');
						}

						// Workshops tonen

						for (key in data['workshops']) {
							var workshop_ID = data['workshops'][key]['workshop_ID'];
							var workshop_titel = data['workshops'][key]['workshop_titel'];

							$('#zoekresultaten table').append('<tr><td class="type">Workshop</td><td>' + workshop_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/workshops/' + workshop_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/workshops/wijzigen/' + workshop_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/workshops/verwijderen/' + workshop_ID + '"></a></td></tr>');
						}

						for (key in data['kennismakingsworkshops']) {
							var kennismakingsworkshop_ID = data['kennismakingsworkshops'][key]['kennismakingsworkshop_ID'];
							var kennismakingsworkshop_titel = data['kennismakingsworkshops'][key]['kennismakingsworkshop_titel'];

							$('#zoekresultaten table').append('<tr><td class="type">Kennismakingsworkshop</td><td>' + kennismakingsworkshop_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/kennismakingsworkshops/' + kennismakingsworkshop_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/kennismakingsworkshops/wijzigen/' + kennismakingsworkshop_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/kennismakingsworkshops/verwijderen/' + kennismakingsworkshop_ID + '"></a></td></tr>');
						}

						// Groepen tonen

						for (key in data['groepen']) {
							var groep_ID = data['groepen'][key]['groep_ID'];
							var groep_naam = data['groepen'][key]['groep_naam'];

							$('#zoekresultaten table').append('<tr><td class="type">Groep</td><td>' + groep_naam + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/groepen/' + groep_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/groepen/wijzigen/' + groep_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/groepen/verwijderen/' + groep_ID + '"></a></td></tr>');
						}

						// Producten tonen

						for (key in data['producten']) {
							var product_ID = data['producten'][key]['product_ID'];
							var product_naam = data['producten'][key]['product_naam'];

							$('#zoekresultaten table').append('<tr><td class="type">Product</td><td>' + product_naam + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/producten/' + product_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/producten/wijzigen/' + product_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/producten/verwijderen/' + product_ID + '"></a></td></tr>');
						}

						// Pagina's tonen

						for (key in data['paginas']) {
							var pagina_ID = data['paginas'][key]['pagina_ID'];
							var pagina_titel = data['paginas'][key]['pagina_titel_menu'];

							$('#zoekresultaten table').append('<tr><td class="type">Pagina</td><td>' + pagina_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/paginas/' + pagina_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/paginas/wijzigen/' + pagina_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/paginas/verwijderen/' + pagina_ID + '"></a></td></tr>');
						}

						// Nieuws tonen

						for (key in data['nieuws']) {
							var nieuws_ID = data['nieuws'][key]['nieuws_ID'];
							var nieuws_titel = data['nieuws'][key]['nieuws_titel'];

							$('#zoekresultaten table').append('<tr><td class="type">Nieuws</td><td>' + nieuws_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/nieuws/' + nieuws_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/nieuws/wijzigen/' + nieuws_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/nieuws/verwijderen/' + nieuws_ID + '"></a></td></tr>');
						}

						// Reacties tonen

						for (key in data['reacties']) {
							var reactie_ID = data['reacties'][key]['reactie_ID'];
							var reactie_titel = data['reacties'][key]['reactie_titel'];
							var reactie_deelnemer = data['reacties'][key]['reactie_deelnemer'];

							$('#zoekresultaten table').append('<tr><td class="type">Reactie</td><td>' + reactie_titel + ' - ' + reactie_deelnemer + '</td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/reacties/' + reactie_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/reacties/wijzigen/' + reactie_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/reacties/verwijderen/' + reactie_ID + '"></a></td></tr>');
						}

						// Vragen tonen

						for (key in data['vragen']) {
							var vraag_ID = data['vragen'][key]['vraag_ID'];
							var vraag_titel = data['vragen'][key]['vraag_titel'];

							$('#zoekresultaten table').append('<tr><td class="type">Vraag</td><td>' + vraag_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/vragen/' + vraag_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/vragen/wijzigen/' + vraag_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/vragen/verwijderen/' + vraag_ID + '"></a></td></tr>');
						}

						// Media tonen

						for (key in data['media']) {
							var media_ID = data['media'][key]['media_ID'];
							var media_type = data['media'][key]['media_type'];
							var media_titel = data['media'][key]['media_titel'];

							$('#zoekresultaten table').append('<tr><td class="type">' + media_type.charAt(0).toUpperCase() + media_type.slice(1) + '</td><td>' + media_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/media/' + media_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/media/wijzigen/' + media_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/media/verwijderen/' + media_ID + '"></a></td></tr>');
						}

						// Aantal resultaten tellen

						if ($('#zoekresultaten table tr').length == 0) {
							$('#zoekresultaten').removeClass('open');
						}
                    } else if(docent == 'media') {
                        // Media tonen

						if(data['media'].length > 0) {
							for (key in data['media']) {
								var media_ID = data['media'][key]['media_ID'];
								var media_type = data['media'][key]['media_type'];
								var media_titel = data['media'][key]['media_titel'];

								$('#zoekresultaten table').append('<tr><td class="type">' + media_type.charAt(0).toUpperCase() + media_type.slice(1) + '</td><td>' + media_titel + '</td><td></td><td></td><td></td><td class="bekijken"><a href="https://localhost/cms/media/' + media_ID + '"></a></td><td class="wijzigen"><a href="https://localhost/cms/media/wijzigen/' + media_ID + '"></a></td><td class="verwijderen"><a href="https://localhost/cms/media/verwijderen/' + media_ID + '"></a></td></tr>');
							}
						} else {
							$('#zoekresultaten table').append('<tr><td class="type"></td><td>Er zijn geen resultaten gevonden</td></tr>');
						}

					} else {
						// Docenten tonen

						for (key in data['docenten']) {
							var docent_ID = data['docenten'][key]['docent_ID'];
							var docent_naam = data['docenten'][key]['docent_naam'];

							$('#zoekresultaten table').append('<tr><td class="type">Docent</td><td>' + docent_naam + '</td><td class="bekijken"><a href="https://localhost/over-ons/"></a></td><td></td><td></td></tr>');
						}

						// Vragen tonen

						for (key in data['vragen']) {
							var vraag_ID = data['vragen'][key]['vraag_ID'];
							var vraag_titel = data['vragen'][key]['vraag_titel'];

							$('#zoekresultaten table').append('<tr><td class="type">Vraag</td><td>' + vraag_titel + '</td><td class="bekijken"><a href="https://localhost/cms/vragen/vragen_docent"></a></td><td></td><td></td></tr>');
						}

						if ($('#zoekresultaten table tr').length == 0) {
							$('#zoekresultaten').removeClass('open');
						}
					}
				}, 'json');
			}
			else
			{
				$('#zoekresultaten').removeClass('open');
				$('#zoekresultaten table tr').remove();
			}
		}
	});
}

///////////////////
// UITNODIGINGEN //
///////////////////

function update_lessen(selecteer_opgeslagen_les)
{
	var workshopID = $('#js_item_workshop').val();

	if (workshopID != '')
	{
		var item_nales = $('#item_nales');

		$.get('/ajax/workshop_lessen/' + workshopID, function(data){
			var nieuwe_les_opties = '<option value="">Selecteer een les</option>';

			if(data.length > 0)
			{
				for(var i = 0; i < data.length; i++)
				{
					var les_id 		= data[i]['les_ID'];
					var les_titel 	= data[i]['les_titel'];

					nieuwe_les_opties += '<option value="' + les_id + '">' + les_titel + '</option>';
				}
			}

			$('#item_nales').html(nieuwe_les_opties);
		}, 'json').done(function() {
			if (selecteer_opgeslagen_les) {
				var les = item_nales.data('les');

				if (les != '') {
					item_nales.val(les);
				}
			}
		});
	}
}

function initWorkshopMetLessen()
{
	$('#js_item_workshop').on('change', function()
	{
		update_lessen();
	});
}

function getWorkshopInfo (value) {
		var workshop_ID = value;

		$.get('/ajax/getWorkshopInfo', { workshop_ID: JSON.stringify(workshop_ID)}, function(data) {
			var index = document.getElementById("item_kopie").selectedIndex;
			document.getElementById("workshopForm").reset();
			document.getElementById("item_kopie").selectedIndex = index;

			if (data['workshop_titel'] !== undefined) { document.getElementById('item_titel').value = data['workshop_titel']; } else { document.getElementById('item_titel').value = ''; }
			if (data['workshop_ondertitel'] !== undefined) { document.getElementById('item_ondertitel').value = data['workshop_ondertitel']; } else { document.getElementById('item_ondertitel').value = ''; }
			if (data['workshop_afkorting'] !== undefined) { document.getElementById('item_afkorting').value = data['workshop_afkorting']; } else { document.getElementById('item_afkorting').value = ''; }
			if (data['workshop_inleiding'] !== undefined) { document.getElementById('item_inleiding').value = data['workshop_inleiding']; } else { document.getElementById('item_inleiding').value = ''; }
			if (data['workshop_beschrijving'] !== undefined) { $(tinymce.get('item_beschrijving').getBody()).html(data['workshop_beschrijving']); } else { document.getElementById('item_beschrijving').value = ''; }
			if (data['workshop_prijs'] !== undefined) { document.getElementById('item_prijs').value = data['workshop_prijs']; } else { document.getElementById('item_prijs').value = ''; }
			if (data['workshop_frequentie'] !== undefined) { document.getElementById('item_frequentie').value = data['workshop_frequentie']; } else { document.getElementById('item_frequentie').value = ''; }
			if (data['workshop_aanmelden_tekst'] !== undefined) { $(tinymce.get('item_aanmelden_tekst').getBody()).html(data['workshop_aanmelden_tekst']); } else { document.getElementById('item_aanmelden_tekst').value = ''; }
			if (data['workshop_praktijkdag_tekst'] !== undefined) { $(tinymce.get('item_praktijkdag_tekst').getBody()).html(data['workshop_praktijkdag_tekst']); } else { document.getElementById('item_praktijkdag_tekst').value = ''; }
			if (data['workshop_stemtest_prijs'] !== undefined) { document.getElementById('item_stemtest_prijs').value = data['workshop_stemtest_prijs']; } else { document.getElementById('item_stemtest_code').value = ''; }
			if (data['workshop_stemtest_code'] !== undefined) { document.getElementById('item_stemtest_code').value = data['workshop_stemtest_code']; } else { document.getElementById('item_stemtest_code').value = ''; }
			if (data['workshop_capaciteit'] !== undefined) { document.getElementById('item_capaciteit').value = data['workshop_capaciteit']; } else { document.getElementById('item_capaciteit').value = ''; }
			if (data['workshop_stemtest_dagen_korting_na_afloop'] !== undefined) { document.getElementById('item_stemtest_dagen_korting_na_afloop').value = data['workshop_stemtest_dagen_korting_na_afloop']; } else { document.getElementById('item_stemtest_dagen_korting_na_afloop').value = ''; }
			if (data['workshop_toelatingseisen'] !== undefined) { document.getElementById('item_toelatingseisen').value = data['workshop_toelatingseisen']; } else { document.getElementById('item_stemtest_code').value = ''; }
			if (data['workshop_inclusief'] !== undefined) { document.getElementById('item_inclusief').value = data['workshop_inclusief']; } else { document.getElementById('item_inclusief').value = ''; }
			if (data['workshop_exclusief'] !== undefined) { document.getElementById('item_exclusief').value = data['workshop_exclusief']; } else { document.getElementById('item_exclusief').value = ''; }
			if (data['workshop_praktijkdag_aanmeldles'] !== undefined) { document.getElementById('item_praktijkdag_aanmeldles').value = data['workshop_praktijkdag_aanmeldles']; } else { document.getElementById('item_praktijkdag_aanmeldles').value = ''; }
			if (data['workshop_duur'] != undefined) { document.getElementById('item_duur').value = data['workshop_duur']; } else { document.getElementById('item_duur').value = ''; }

		}, 'json');
}

function toggle(source) {
	unchecked = false;

	switch(source.name) {
		case 'kennismakingsworkshops_checkbox':
			checkboxes = document.getElementsByName('geselecteerde_kennismakingsworkshops[]');
			break;
		case 'workshops_checkbox':
			checkboxes = document.getElementsByName('geselecteerde_workshops[]');
			break;
		case 'producten_checkbox':
			checkboxes = document.getElementsByName('geselecteerde_producten[]');
			break;
		case 'notities_checkbox':
			checkboxes = document.getElementsByName('geselecteerde_notities[]');
			break;
		case 'kortingscodes_checkbox':
			checkboxes = document.getElementsByName('geselecteerde_kortingscodes[]');
			break;
        case 'verzonden_berichten_checkbox':
        	checkboxes = document.getElementsByClassName('verzonden_berichten_checkbox');
        break;
        case 'inkomende_berichten_checkbox':
            checkboxes = document.getElementsByClassName('inkomende_berichten_checkbox');
			break;
		case 'geselecteerde_lessen_checkbox':
			checkboxes = document.getElementsByClassName('geselecteerde_lessen_checkbox');
			break;
        case 'geen_checkbox':
            checkboxes_workshops = document.getElementsByName('geselecteerde_workshops[]');
            checkboxes_products = document.getElementsByName('geselecteerde_producten[]');
            unchecked = true;
            break;
	}

	if(!unchecked) {
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    } else {
        for (var i = 0, n = checkboxes_workshops.length; i < n; i++) {
            checkboxes_workshops[i].checked = false;
        }

        for (var i = 0, n = checkboxes_products.length; i < n; i++) {
            checkboxes_products[i].checked = false;
        }

        document.getElementsByName('workshops_checkbox')[0].checked = false;
        document.getElementsByName('products_checkbox')[0].checked = false;
	}
}

function update_groep(source) {
	if($('#filter_groep_div').length) {
		var item = document.getElementById('filter_groep_div');
		if (source.name == 'filter_archief') {
			source = document.getElementById('filter_workshop');
		}

		if (document.getElementById('filter_archief1').checked) {
			archief = 1;
		} else {
			archief = 0;
		}

		if(source.value != undefined) {
			$.get('/ajax/update_groep_select', { id: JSON.stringify(source.value), archief: JSON.stringify(archief)}, function(data) {
				var select = document.getElementById('filter_groep');
					select.options.length=2;
					select.options[0] = new Option('Selecteer groep', '', false, false);
					select.options[1] = new Option('---', '', false, false);


				if(data.groepen.length > 0) {
					for(var i = 0; i < data.groepen.length; i++) {
						var objOption = document.createElement("option");
						var x = i + 2;
						objOption.text = data.groepen[i].groep_naam;
						objOption.value = data.groepen[i].groep_ID;

						select.options[x] = new Option(objOption.text, objOption.value, false, false)
					}
					item.style.display = "block";
				} else {
					item.style.display = "none";
				}
			}, 'json');
		}
	}
}

function update_groep_keuze(source) {
    var item = document.getElementById('filter_groep_div');

    if(source.value != undefined) {
        $.get('/ajax/update_groep_select', { id: JSON.stringify(source.value), archief: JSON.stringify(0)}, function(data) {
            var select = document.getElementById('filter_groep');
            select.options.length=2;
            select.options[0] = new Option('Selecteer groep', '', false, false);
            select.options[1] = new Option('---', '', false, false);


            if(data.groepen.length > 0) {
                for(var i = 0; i < data.groepen.length; i++) {
                    var objOption = document.createElement("option");
                    var x = i + 2;
                    objOption.text = data.groepen[i].groep_naam;
                    objOption.value = data.groepen[i].groep_ID;

                    select.options[x] = new Option(objOption.text, objOption.value, false, false)
                }
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        }, 'json');
    }
}

function update_template() {
	var selected = document.getElementById('item_templates').value;
 	if(selected.length > 0) {
		$.get('/ajax/getTemplates', function (data) {
			for (var i = 0; i < data.templates.length; i++) {
				if (data.templates[i].template_ID == selected) {
					if ($('#item_templates').length) {
						document.getElementById('template_naam').value = data.templates[i].template_titel;
						$(tinymce.get('item_tekst').getBody()).html("<p>" + data.templates[i].template_tekst);
						break;
					}
				} else {
					document.getElementById('template_naam').value = "";
					$(tinymce.get('item_tekst').getBody()).html("<p>");
				}
			}
		}, 'json');
 	}
}

function template_aanpassen() {
	var template_ID = document.getElementById('item_templates').value;
	var template_naam = document.getElementById('template_naam').value;
	var template_tekst =  $.trim(tinymce.get('item_tekst').getContent());

	$.get('/ajax/opslaanTemplate',{template_ID: template_ID, template_naam: JSON.stringify(template_naam), template_tekst: JSON.stringify(template_tekst)}, function (data) {
		if (data.toegevoegd == true) {
			alert('Template toegevoegd');
			update_template_filter();
		} else {
			alert('Er ging iets mis probeer het opnieuw');
			update_template_filter();
		}
	}, 'json');
}

function update_template_filter() {
	$.get('/ajax/getTemplates', function(data) {
		var select = document.getElementById('item_templates');
		select.options.length=2;
		select.options[0] = new Option('Selecteer een template', '', false, false);
		select.options[1] = new Option('---', '', false, false);


		if(data.templates.length > 0) {
			for(var i = 0; i < data.templates.length; i++) {
				var objOption = document.createElement("option");
				var x = i + 2;
				objOption.text = data.templates[i].template_titel;
				objOption.value = data.templates[i].template_ID;

				select.options[x] = new Option(objOption.text, objOption.value, false, false)
			}
		}
	}, 'json');
}

function korting_type(first) {
	if( $('#korting_percentage, #korting_vast_bedrag').length ) {
		if (document.getElementById('korting_percentage').checked) {
			var item = document.getElementById('vast_bedrag');
			var other = document.getElementById('percentage');
			var value = document.getElementById('item_percentage');

			other.style.display = "block";
			item.style.display = "none";

			if (first == undefined) {
				value.value = '';
			}
		}

		if (document.getElementById('korting_vast_bedrag').checked) {
			var item = document.getElementById('percentage');
			var other = document.getElementById('vast_bedrag');
			var value = document.getElementById('item_vast_bedrag');

			other.style.display = "block";
			item.style.display = "none";

			if (first == undefined) {
				value.value = '';
			}
		}
	}
}

function insertAanwezigheid(les_ID, gebruiker_ID, value) {
    $.get('/ajax/insertAanwezigheid', {les_ID: JSON.stringify(les_ID), gebruiker_ID: JSON.stringify(gebruiker_ID), value: JSON.stringify(value)}, function (data) {
        alert('Aanwezigheid gewijzigd!');
	}, 'json');
}

function audio_type() {
	if($('#audio_mp3, #audio_opnemen, #audio_WAV').length ) {
		if (document.getElementById('audio_mp3')) {
			if (document.getElementById('audio_mp3').checked) {
				var item = document.getElementById('audio_record');
				var other = document.getElementById('audio_src');

				other.style.display = "block";
				item.style.display = "none";
			}
		}

		if (document.getElementById('audio_WAV')) {
			if (document.getElementById('audio_WAV').checked) {
				var item = document.getElementById('audio_record');
				var other = document.getElementById('audio_src');

				other.style.display = "block";
				item.style.display = "none";
			}
		}

		if (document.getElementById('audio_opnemen')) {
			if (document.getElementById('audio_opnemen').checked) {
				var item = document.getElementById('audio_src');
				var other = document.getElementById('audio_record');

				other.style.display = "block";
				item.style.display = "none";
			}
		}
	}
}

function AddItem() {
	if(document.getElementById('item_toevoegen')) {
        var select = document.getElementById('item_toevoegen');
		var tag = '';

        if(select.value == 1) {
            tag = '[TAB-TITEL-BEGIN] [TAB-TITEL-EIND] [TAB-BEGIN] [TAB-EIND]';
        } else if (select.value == 2) {
            tag = '[BORDER-BEGIN] [BORDER-EIND]';
        } else if(select.value == 3) {
            tag = '[BLAUWE-ACHTERGROND-BEGIN] [BLAUWE-ACHTERGROND-EIND]';
        } else if(select.value == 4) {
        	tag = '[VINKJE]';
    	} else if(select.value == 5) {
            tag = '[LINK-BEGIN][LINK-EIND][LINK-TEKST-BEGIN][LINK-TEKST-EIND]';
        } else if(select.value == 6) {
            tag = '[BUTTON-BEGIN][BUTTON-EIND][BUTTON-TEKST-BEGIN][BUTTON-TEKST-EIND]';
        }

		if(document.getElementById('item_beschrijving')) {
            tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
		} else if(document.getElementById('item_tekst')) {
            tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
		}
    }
}

function showVerhinderd() {
    div = document.getElementById('verhinderd');

    if(div.style.display == "block") {
        div.style.display = "none";
    } else {
        div.style.display = "block";
	}
}

function showAnders(value) {
    input = document.getElementById('item_adres_anders');

    if (value == 4) {
        input.style.display = "block";
    } else {
        input.style.display = "none";
    }
}

function printContent(el){
	var restorepage = document.body.innerHTML;

	if (el == 'deelnemerslijst') {
		var ids = document.getElementById(el).value;

		if (ids.length > 0) {
			$.get('/ajax/getDeelnemers', {ids: JSON.stringify(ids)}, function (data) {
				var printcontent = '<table><th>Naam</th><th>Geslacht</th><th>Telefoonnummer</th><th>Mobiel</th><th>Email</th>';

				for (var i = 0; i < data.length; i++) {
					var naam = data[i]['gebruiker_naam'];
					var geslacht = data[i]['gebruiker_geslacht'];
					var telefoonnummer = data[i]['gebruiker_telefoonnummer'];
					var mobiel = data[i]['gebruiker_mobiel'];
					var email = data[i]['gebruiker_emailadres'];

					printcontent += '<tr><td>' + naam + '</td><td>' + geslacht + '</td><td>' + telefoonnummer + '</td><td>' + mobiel + '</td><td>' + email + '</td></tr>';
				}

				printcontent += '</table>';

                var Browser = navigator.userAgent;

                if(Browser.indexOf("Chrome") > -1 || Browser.indexOf("MSIE") > -1 || Browser.indexOf("Opera") > -1 || Browser.indexOf("Firefox") > -1) {
                    document.body.innerHTML = printcontent;
                    window.print();
                    document.body.innerHTML = restorepage;
                } else if (Browser.indexOf("Safari") > -1) {
                    var myWindow=window.open('','','width=1,height=1');
                    myWindow.document.write(printcontent);

                    myWindow.document.close();
                    myWindow.focus();
                    myWindow.print();
                    myWindow.close();
                }
			}, 'json');
		} else {
			alert('Kan deelnemerslijst niet printen');
		}
	}

	if(el != 'deelnemerslijst') {
		var groep_ID = el;
		var groep_naam = document.getElementById('groep_naam').innerHTML;

		if(groep_naam.length > 0) {
			$.get('/ajax/getDeelnemers', {groep_ID: JSON.stringify(groep_ID)}, function (data) {
				var printcontent = '<h2>' + groep_naam + '</h2><table style="text-align:left;"><th>Naam</th><th>Telefoonnummer</th><th>Mobiel</th>';

				for (var i = 0; i < data.deelnemers.length; i++) {
					var naam = data.deelnemers[i]['gebruiker_naam'];
					var telefoonnummer = data.deelnemers[i]['gebruiker_telefoonnummer'];
					var mobiel = data.deelnemers[i]['gebruiker_mobiel'];

					printcontent += '<tr><td>' + naam + '</td><td>' + telefoonnummer + '</td><td>' + mobiel + '</td></tr>';
				}

				printcontent += '</table>';

                var Browser = navigator.userAgent;

                if(Browser.indexOf("Chrome") > -1 || Browser.indexOf("MSIE") > -1 || Browser.indexOf("Opera") > -1 || Browser.indexOf("Firefox") > -1) {
                    document.body.innerHTML = printcontent;
                    window.print();
                    document.body.innerHTML = restorepage;
                } else if (Browser.indexOf("Safari") > -1) {
                    var myWindow=window.open('','','width=1,height=1');
                    myWindow.document.write(printcontent);

                    myWindow.document.close();
                    myWindow.focus();
                    myWindow.print();
                    myWindow.close();
                }
			}, 'json');
		} else {
			alert('Kan groep niet printen')
		}
	}
}

function printDeelnemer(el) {
	var restorepage = document.body.innerHTML;

	if(el != '' || el != undefined) {
		var deelnemer_naam = document.getElementById('deelnemer_naam').innerHTML;

		if(deelnemer_naam.length > 0) {
			$.get('/ajax/getDeelnemer', {deelnemer_ID: JSON.stringify(el)}, function (data) {
				var printcontent = '<h2>' + deelnemer_naam + '</h2>';
				for (var i = 0; i < data['items'].length; i++) {
					printcontent += data['items'][i];
				}

                var Browser = navigator.userAgent;

                if(Browser.indexOf("Chrome") > -1 || Browser.indexOf("MSIE") > -1 || Browser.indexOf("Opera") > -1 || Browser.indexOf("Firefox") > -1) {
                    document.body.innerHTML = printcontent;
                    window.print();
                    document.body.innerHTML = restorepage;
                } else if (Browser.indexOf("Safari") > -1) {
                    var myWindow=window.open('','','width=1,height=1');
                    myWindow.document.write(printcontent);

                    myWindow.document.close();
                    myWindow.focus();
                    myWindow.print();
                    myWindow.close();
                }
			}, 'json');
		} else {
			alert('kan deelenemer niet printen');
		}
	} else {
		alert('kan deelnemer niet printen');
	}
}

function formatDate(date) {
	var maanden = [
		"Januari", "Februari", "Maart",
		"April", "Mei", "Juni", "Juli",
		"Augustus", "September", "Oktober",
		"November", "December"
	];

	var dag = date.getDate();
	var maandenindex = date.getMonth();
	var jaar = date.getFullYear();

	return dag + ' ' + maanden[maandenindex] + ' ' + jaar;
}

function updateRating(radio ,el, gebruiker_ID, les_ID) {
    var radio = radio;

    $('.' + gebruiker_ID + ' .rating_gebruiker .selected').removeClass('selected');
    radio.closest('label').addClass('selected');

    $.get('/ajax/insertRatingAdmin', { gebruiker_ID: JSON.stringify(gebruiker_ID), les_ID: JSON.stringify(les_ID), beoordeling: JSON.stringify(el.value)}, function(data) {
        alert('Bedankt voor de feedback!');
    }, 'json');
}

function updateVorigeLesRating() {
    if(confirm('Weet je zeker dat je de beoordeling wilt opslaan?')) {
        var radio = $("input[type='radio'][name='vorige_les_rating']:checked").val();
        var gebruiker_ID = document.getElementById('gebruiker_ID').value;
        var vorige_les_ID = document.getElementById('vorige_les_ID').value;
        var opmerking = document.getElementById('beoordeling_tekst').value;

        if ((!opmerking && radio > 3) || (opmerking && radio < 4)) {
            $.get('/ajax/insertRating', {
                gebruiker_ID: JSON.stringify(gebruiker_ID),
                les_ID: JSON.stringify(vorige_les_ID),
                beoordeling: JSON.stringify(radio),
                opmerking: JSON.stringify(opmerking)
            }, function (data) {
                alert('Bedankt voor de feedback!');
                document.getElementById('myModal').style.display = "none";
            }, 'json');
        } else {
            alert('Vul alsjeblieft een opmerking in!')
        }
    }
}

function nextInDOM(_selector, _subject) {
    var next = getNext(_subject);
    while(next.length != 0) {
        var found = searchFor(_selector, next);
        if(found != null) return found;
        next = getNext(next);
    }
    return null;
}
function getNext(_subject) {
    if(_subject.next().length > 0) return _subject.next();
    return getNext(_subject.parent());
}
function searchFor(_selector, _subject) {
    if(_subject.is(_selector)) return _subject;
    else {
        var found = null;
        _subject.children().each(function() {
            found = searchFor(_selector, $(this));
            if(found != null) return false;
        });
        return found;
    }
    return null; // will/should never get here
}

function leeftijdcheck() {
	var dag = document.getElementById("aanmelden_geboortedatum_dag");
	var maand = document.getElementById("aanmelden_geboortedatum_maand");
	var jaar = document.getElementById("aanmelden_geboortedatum_jaar");

	var dateString = jaar.value+"-"+maand.value+"-"+dag.value;
	var leeftijd = getLeeftijd(dateString);

	if(leeftijd < 18) {
		return confirm("Het lijkt erop dat jij te jong bent voor deelname aan deze workshop. Neem s.v.p. vóór inschrijving even contact met ons op via info@localhost");
	}
}

function getLeeftijd(dateString) {
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
	}
    return age;
}

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");

		if (n > x.length) {slideIndex = 1}
			if (n < 1) {slideIndex = x.length}
			for (i = 0; i < x.length; i++) {
				x[i].style.display = "none";
			}
			for (i = 0; i < dots.length; i++) {
				dots[i].className = dots[i].className.replace(" s-button-orange", "");
			}

		if(x[slideIndex-1] != undefined) {
			if(dots[slideIndex-1] != undefined) {
			x[slideIndex-1].style.display = "block";
			dots[slideIndex-1].className += " s-button-orange";
			}
		}
}

///////////
// Ready //
///////////

$(document).ready(function(index)
{
	imagePreview();
	if(document.getElementById('aanmelden_formulier_kort')) {
		if(document.getElementById('aanmelden_formulier_kort').checked) {
			toonFormulier('kort');
		}else if(document.getElementById('aanmelden_formulier_lang').checked) {
			toonFormulier('lang');
		}
	}

	korting_type('first');
	audio_type();
	stemtestCodeToggle('Nee');
	document.querySelectorAll('img').forEach(function(img){
		img.onerror = function(){this.style.display='none';};
	})

	nav_open = 1;
	if(document.getElementById("mySidenav")) {
	openNav();
	}

    $('#tab li').click(function () {
        this.classList.toggle("active");
	});

	$('#toggle-vimeo-chat').click(function () {
		let vimeoChat = document.getElementById('vimeo-chat-iframe');
		vimeoChat.classList.toggle("closed");
		this.classList.toggle("closed");
    });

	if(document.getElementById('item_adres_anders')) {
        var value = document.querySelector('input[name="item_locatie_ID"]:checked').value;
        showAnders(value);
    }

	if(document.getElementById('item_rechten') && document.getElementById('opleidingsmedewerker')) {
		var el = document.getElementById('item_rechten');
        showDocentfields(el.value);
    }

    $('.rating input').change(function () {
        var radio = $(this);
        $('.rating .selected').removeClass('selected');
        radio.closest('label').addClass('selected');
        var gebruiker_ID = document.getElementById('gebruiker_ID').value;
        var les_ID = document.getElementById('les_ID').value;

        $.get('/ajax/insertRating', { gebruiker_ID: JSON.stringify(gebruiker_ID), les_ID: JSON.stringify(les_ID), beoordeling: JSON.stringify(radio.val())}, function(data) {
			alert('Bedankt voor de feedback!');
        }, 'json');
    });

    $('.vorige_les_rating input').change(function () {
        var radio = $(this);
        $('.vorige_les_rating .selected').removeClass('selected');
        radio.closest('label').addClass('selected');

        if (radio.val() > 3) {
            div = document.getElementById('opmerking');
            div.style.display = "none";
		} else {
        	div = document.getElementById('opmerking');
        	div.style.display = "block";
		}
    });

	if(document.getElementById('item_ontvanger')) {
		$("#item_ontvanger").select2();
	}

	if(document.getElementById('tab_recent-workshops')) {
		if ($('#tab_archief').hasClass('active') == true) {
			document.getElementById('archief').style.display = "block";
			document.getElementById('recent-workshops').style.display = "none";
		} else {
			document.getElementById('recent-workshops').style.display = "block";
			document.getElementById('archief').style.display = "none";
		}
	}

	if(document.getElementById('tab_actief')) {
		if ($('#tab_archief').hasClass('active') == true) {
			document.getElementById('archief').style.display = "block";
			if(document.getElementById('tab_groepen')) {
				document.getElementById('groepen').style.display = "none";
			}
			document.getElementById('actief').style.display = "none";
			document.getElementById('aanmelden').style.display = "none";
		} else if($('#tab_groepen').hasClass('active') == true) {
			if(document.getElementById('tab_groepen')) {
				document.getElementById('groepen').style.display = "block";
			}
			document.getElementById('archief').style.display = "none";
			document.getElementById('actief').style.display = "none";
			document.getElementById('aanmelden').style.display = "none";
		} else if($('#tab_aanmelden').hasClass('active') == true) {
			if(document.getElementById('tab_aanmelden')) {
				document.getElementById('aanmelden').style.display = "block";
			}
			document.getElementById('archief').style.display = "none";
			document.getElementById('actief').style.display = "none";
			document.getElementById('groepen').style.display = "none";
		} else {
			if(document.getElementById('tab_groepen')) {
				document.getElementById('groepen').style.display = "none";
			}
			document.getElementById('archief').style.display = "none";
			document.getElementById('actief').style.display = "block";
			document.getElementById('aanmelden').style.display = "none";
		}
	}

	if(document.getElementById('tab_inbox')) {
        if ($('#tab_verzonden').hasClass('active') == true) {
            document.getElementById('verzonden').style.display = "block";
            document.getElementById('inbox').style.display = "none";
        } else {
            document.getElementById('inbox').style.display = "block";
            document.getElementById('verzonden').style.display = "none";
        }
	}

	if(document.getElementsByName('huur_producten[]').length) {
		checkboxes = document.getElementsByName('huur_producten[]')
		geselecteerd = false;

        for (var i = 0, n = checkboxes.length; i < n; i++) {
			if(checkboxes[i].checked == true) {
				geselecteerd = true;
			}
        }

        if (geselecteerd) {
			container = document.getElementsByClassName('akkoord_container');
			for (i = 0; i < container.length; i++) {
				container[i].style.display = "block";
			}

        } else {
			container = document.getElementsByClassName('akkoord_container');
			for (i = 0; i < container.length; i++) {
				container[i].style.display = "none";
			}
        }
    }

    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = "none";
            }
        }
    }

    // Get the modal
    var modal = document.getElementById('myModal');

    if(modal) {
        modal.style.display = "block";
    }

	if(document.getElementById('totaal')) {
		if($('.prijs').length > 0) { prijs = document.getElementById('totaal').innerHTML; }
	}
	if($('.betaalmethode-creditcard').length > 0) { $('.betaalmethode-creditcard').hide(); }
	if($('#aanmelden').length > 0) { initAanmelden(); }
	if($('#docenten').length > 0) { initDocenten(); }
	if($('#vragen').length > 0) { initVragen(); }
	if($('.audio-player').length > 0) { initAudio(); }
	if($('#audio').length > 0) { initAudio(); }

	if($('#filter_groep_div').length) {
		var filter_groep = document.getElementById('filter_groep');
		var laatste_groep = document.getElementById('laatste_groep');

		if (filter_groep.options.length < 3) {
			document.getElementById('filter_groep_div').style.display = "none";
		}

		for(var i = 0; i < filter_groep.options.length; i++) {
			if(filter_groep.options.groep_ID == laatste_groep.value) {
				filter_groep.selectedIndex = i;
			}
		}
	}

	$.get('/ajax/update_prijs', { prijs: JSON.stringify(prijs) }, function(data) {
        var workshop_ID = 0;

        if(document.getElementById('workshop_ID')) {
            workshop_ID = document.getElementById('workshop_ID').value;
        }

		for(var i = 0; i < data.length; i++)
		{
			var pay_ID = data[i]['id'];
			var nieuwe_prijs = data[i]['prijs'];
		}
	}, 'json');

	$("#item_kopie").change(function(){
		var id = $(this).find("option:selected").attr("value");

		if(id == 0) {
			document.getElementById("workshopForm").reset();
		}
	});


	$('input[type="radio"]').change(function() {
		if(document.getElementById('totaal')) {
			if($(this).attr('value') == '706')  {

			} else {
                document.getElementById('totaal').innerHTML = prijs;
                $('.betaalmethode-creditcard').hide();
			}
		}
	});

	// CMS

	if($('#keuzes').length > 0) { initKeuzes(); }

	sorteerRijen();
	if($('.opmaak').length > 0) { initTinyMCE(); }
	if($('.opmaak_simpel').length > 0) { initTinyMCE(); }
	if($('#item_url').length > 0) { initURL(); }
	if($('#zoeken').length > 0 && !$('#zoeken_docent').length && !$('#zoeken_media').length){ initZoeken(); }
	if($('#zoeken_docent').length > 0){ initZoeken(true); }
	if($('#zoeken_media').length > 0){ initZoeken('media'); }
	if($('#js_item_workshop').length >0) { update_lessen(true); initWorkshopMetLessen(); }

	if($('.koppelen').length > 0) {
		$('.koppelen').click(function(e){ e.preventDefault(); openMediabibliotheek($(this).data('soort'), $(this).data('welkomstmail'), $(this).data('specific_sort')); });
	}

	if($('.ontkoppelen').length > 0) {
		$('.ontkoppelen').click(function(e){ e.preventDefault(); ontkoppelen(); });
	}

	if ($('.product-meerinfo-js').length > 0) {
		$('.product-meerinfo-js').on('click', function(e) {
			var product_beschrijving = $(this).closest('.product-beschrijving');
			var info_lang = product_beschrijving.children('.product-beschrijving-lang-js');
			var info_kort = product_beschrijving.children('.product-beschrijving-kort-js');

			// Meer of minder product info tonen afhankelijk van welke tekst zichbaar is
			if (info_lang.is(":visible")) {
				info_lang.hide();
				info_kort.show();

				$(this).text('Klik hier voor meer informatie');
			} else {
				info_lang.show();
				info_kort.hide();

				$(this).text('Klik hier voor minder informatie');
			}

			e.preventDefault();
		});
	}
});
