function getXmlHttp() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest();
	}   return xmlhttp;
}

function number_format(number, decimals, point, thousands_sep) 
{	// Format a number with grouped thousands
	var i, j, kw, kd, km;
	// input sanitation & defaults
	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 0;
	}
	if( point == undefined ){
		point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = " ";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
    j = (j = i.length) > 3 ? j % 3 : 0;

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep); //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
	kd = (decimals ? point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");
	return km + kw + kd;
}

function setLazy()
{
    lazy = document.querySelectorAll('img[loading="lazy"]');
    console.log('Found '+lazy.length+' lazy images');
}

function lazyLoad()
{
	lazy.forEach(function(img)
	{
		if(isInViewport(img))
		{
			if (img.hasAttribute('data-src')) {
				img.src = img.getAttribute('data-src');
				img.removeAttribute('data-src');
			}
		}
	});
    
    cleanLazy();
}

function cleanLazy(){
    lazy = Array.prototype.filter.call(lazy, function(e){ 
		return e.getAttribute('data-src');
	});
}

function isInViewport(el){
    var rect = el.getBoundingClientRect();
    return (
		rect.bottom >= 0 && 
		rect.right >= 0 && 
		rect.top <= (window.innerHeight || document.documentElement.clientHeight) && 
		rect.left <= (window.innerWidth || document.documentElement.clientWidth)
	);
}

function registerListener(ev, func) {
    if (window.addEventListener) {
        window.addEventListener(ev, func)
    } else {
        window.attachEvent('on' + ev, func)
    }
}

function print(output){
	document.write(output);
}

registerListener('load', setLazy);
registerListener('load', lazyLoad);
registerListener('scroll', lazyLoad);
