//
// JomTube! AJAX
//
function XmlHttpCreate()
{
	var xmlhttp = null;

	if (window.ActiveXObject)
	{
		try
		{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				alert("You need to enable active scripting and activeX controls.");
			}
		}
	}
	else if (window.XMLHttpRequest)
	{
		try
		{
			xmlhttp = new XMLHttpRequest();
		}
		catch (e)
		{
			alert("Error creating XMLHttpRequest object.");
		}
	}
	else
	{
		alert("XMLHttp is not supported on this browser.");
	}

	return xmlhttp;
}

function XmlHttpGET(xmlhttp, url)
{
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}

function XmlHttpPOST(xmlhttp, url, data)
{
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.setRequestHeader('Content-Length', data.length);
	xmlhttp.send(data);
}


/**
 * Utility class to facilitate encoding of post data
 */
function XmlHttpPostDataEncoder()
{
	this.data = new Array();
}

XmlHttpPostDataEncoder.prototype.addData = function(key, value)
{
	this.data[key] = value;
};

XmlHttpPostDataEncoder.prototype.encodeData = function()
{
	var encData = '';

	for (var i in this.data)
	{
		if (encData.length > 0) encData += '&';
		encData += encodeURIComponent(i) + '=' + encodeURIComponent(this.data[i]);
	}

	return encData;
};


function jomtubeAJAX()
{

}

jomtubeAJAX.prototype.init = function ()
{

}

jomtubeAJAX.prototype.rateVideo = function(videoID, userID, rating)
{
	var thisRef = this;
	var xmlHttp = XmlHttpCreate();

	xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				if (xmlHttp.status == 200)
				{
					try
					{
						document.getElementById('ajaxRating').innerHTML = xmlHttp.responseText;
					}
					catch (e) {
						alert('AJAX Error:\n\n' + e);
					}
				}
				else
				{
					alert('AJAX Error:\n\n' + xmlHttp.responseText.substr(200));
				}
			}
		};

	var dataEncoder = new XmlHttpPostDataEncoder();
	dataEncoder.addData('option', 'com_jomtube');
	dataEncoder.addData('task', 'rate_video');
	dataEncoder.addData('format', 'raw');
	dataEncoder.addData('id', videoID);
	dataEncoder.addData('user_id', userID);
	dataEncoder.addData('rating', rating);

	XmlHttpPOST(xmlHttp, 'index.php', dataEncoder.encodeData());
}


jomtubeAJAX.prototype.featureVideo = function(videoID, addFeatured)
{
	var thisRef = this;
	var xmlHttp = XmlHttpCreate();

	xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				if (xmlHttp.status == 200)
				{
					try
					{
						document.getElementById('markFeatured').innerHTML = xmlHttp.responseText;
					}
					catch (e) {
						alert('AJAX Error!');
					}
				}
				else
				{
					alert('AJAX Error:\n\n' + xmlHttp.responseText.substr(200));
				}
			}
		};


	var dataEncoder = new XmlHttpPostDataEncoder();
	dataEncoder.addData('option', 'com_jomtube');
	if (addFeatured == "true") {
		dataEncoder.addData('task', 'add_featured');
	} else {
		dataEncoder.addData('task', 'remove_featured');
	}
	dataEncoder.addData('format', 'raw');
	dataEncoder.addData('id', videoID);

	XmlHttpPOST(xmlHttp, 'index.php', dataEncoder.encodeData());
}

jomtubeAJAX.prototype.publishVideo = function(videoID, publishVideo)
{
	var thisRef = this;
	var xmlHttp = XmlHttpCreate();

	xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				if (xmlHttp.status == 200)
				{
					try
					{
						document.getElementById('publishVideo').innerHTML = xmlHttp.responseText;
					}
					catch (e) {
						alert('AJAX Error!');
					}
				}
				else
				{
					alert('AJAX Error:\n\n' + xmlHttp.responseText.substr(200));
				}
			}
		};


	var dataEncoder = new XmlHttpPostDataEncoder();
	dataEncoder.addData('option', 'com_jomtube');
	if (publishVideo == "true") {
		dataEncoder.addData('task', 'publishVideo');
	} else {
		dataEncoder.addData('task', 'unpublishVideo');
	}
	dataEncoder.addData('format', 'raw');
	dataEncoder.addData('id', videoID);

	XmlHttpPOST(xmlHttp, 'index.php', dataEncoder.encodeData());
}

function highlightStars(starMap, fullStar, halfStar, emptyStar) {
	var star1 = document.getElementById('videoStar1');
	var star2 = document.getElementById('videoStar2');
	var star3 = document.getElementById('videoStar3');
	var star4 = document.getElementById('videoStar4');
	var star5 = document.getElementById('videoStar5');

	// starmap: 0 = empty, 1 = half, 2 = full
	star1.src = starMap.charAt(0) == "2" ? fullStar : starMap.charAt(0) == "1" ? halfStar : emptyStar;
	star2.src = starMap.charAt(1) == "2" ? fullStar : starMap.charAt(1) == "1" ? halfStar : emptyStar;
	star3.src = starMap.charAt(2) == "2" ? fullStar : starMap.charAt(2) == "1" ? halfStar : emptyStar;
	star4.src = starMap.charAt(3) == "2" ? fullStar : starMap.charAt(3) == "1" ? halfStar : emptyStar;
	star5.src = starMap.charAt(4) == "2" ? fullStar : starMap.charAt(4) == "1" ? halfStar : emptyStar;
}
