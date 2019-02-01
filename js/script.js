$('.article_link').click(function (e) {	
	var artikelnummer = $(this).attr('artikelnummer');
	var sid = $(this).attr('sid');
	getArticle(artikelnummer, sid);
	$('#article').show();
})


$('#pdf').click(function (e) {
	e.preventDefault();
	w = window.open();
	w.document.write('<link rel="stylesheet" type="text/css" href="css/print.css">');
	w.document.write(document.getElementById('articles').outerHTML);
	w.setTimeout(function(){
		w.print();
		w.close();
	}, 1000);
});


$('select, input').on('change', function(){
	$("#submit").trigger('click');
});

function getFullDate() {
	var date = new Date();
	return date.getDate() + '-' +date.getMonth() + '-' +date.getFullYear()+'_'+date.getHours()+'-'+date.getMinutes()+'-'+date.getSeconds();
}

$(document).on('keyup', function(e) { if (e.keyCode == 27) { closePopup(); } });
$(document).on('mouseup', function(e) { var container = $('#article'); if (!container.is(e.target) && container.has(e.target).length === 0 && container.length > 0) {closePopup(); } });

function getArticle(artikelnummer, sid){
	$.ajax({
		url: 'article_json.php?Artikelnummer='+artikelnummer+'&statistikSaison='+sid,
		type: "GET",
		dataType: 'json',
        beforeSend: function(){
        	getPopup();
        	ajaxBefore();
        	$('#article').show();
        },
        success: function( data, textStatus, jQxhr ){
        	$('#article').html('');
        	if (data.error_msg) { 
        		var html = '<div class="error"><span>'+data.error_msg+'</span></div>'; 
        	} else {
        		var result = data[0][0];
        		var keys = Object.keys(data);
        		keys.shift();
        		var html = '<header>Artikel Info<span class="close">X</span></header>';
    			html += '<div id="content1" class="container"><figure><img style="max-width: 300px; max-height: 200px;" src="http://wawineu.spohr.local/artikelverwaltung/imgArtikel/'+result.ArtikelNummer+'.jpg">';
			    html += '<table class="primary"><tr><td>Matchcode</td><td><span>'+result.Matchcode+'</span></td></tr>';
			    html += '<tr><td>ArtSID</td><td><span>'+result.ArtSID+'</span></td></tr>';
			    html += '<tr><td>ArtikelNummer</td><td><span>'+result.ArtikelNummer+'</span></td></tr>';
			    html += '<tr><td>Fakt</td><td><span>'+result.Fakt+'</span></td></tr>';
			    html += '<tr><td>Lager</td><td><span>'+result.Lager+'</span></td></tr>';
			    html += '<tr><td>AbvQ</td><td><span>'+result.AbvQ+'</span></td></tr></table></figure></div>';
	        	html += '<div id="content2" class="container"><table width="100px" class="secondary first"><tr><td width="150">LiefArt</td><td><span>'+result.LiefArt+'</span></td></tr>';
	        	html += '<tr><td width="150">LiefFb</td><td><span>'+result.LiefFb+'</span></td></tr>';
	        	html += '<tr><td width="150">LiefMat</td><td><span>'+result.LiefMat+'</span></td></tr>';
	        	html += '<tr><td width="150">Mod</td><td><span>'+result.Mod+'</span></td></tr>';
	        	html += '<tr><td width="150">RST</td><td><span>'+result.RST+'</span></td></tr>';
	        	html += '<tr><td width="150">Lieferbar</td><td><span>'+result.Lieferbar+'</span></td></tr>';
	        	html += '<tr><td width="150">OffLief</td><td><span>'+result.OffLief+'</span></td></tr>';
	        	if (sid == 'SID') {
	        		html += '<tr><td width="150">OffLief2:</td><td><span>'+result.OffLief2+'</span></td></tr>';
	        	}
	        	html += '<tr><td width="150">RetourQ</td><td><span>'+result.RetourQ+'</span></td></tr>';
	        	html += '<tr><td width="150">ReklaQ</td><td><span>'+result.ReklaQ+'</span></td></tr>';
	        	html += '<tr><td width="150">€EKPaarBruttoMin</td><td><span>'+result.EKPaarBruttoMin+'</span></td></tr>';
	        	html += '<tr><td width="150">€EKPaarBruttoMax</td><td><span>'+result.EKPaarBruttoMax+'</span></td></tr>';
	        	if (sid == 'SID') {
		        	html += '<tr><td width="150">€Rohertrag</td><td><span>'+result.Rohertrag.toFixed(2)+'</span></td></tr>';
		        	html += '<tr><td width="150">€Kalk Kosten</td><td><span>'+result.Kalk_Kosten.toFixed(2)+'</span></td></tr>';
		        	html += '<tr><td width="150">€Kalk Ertrag</td><td><span>'+result.Kalk_Ertrag.toFixed(2)+'</span></td></tr>';
	        	}
	        	html += '</table></div>';

	        	delete data[0];

	        	html += '<div id="content3" class="container">';
	       		html += '<div id="tabs" class="left">';
	        	keys.forEach(function(key){
	        		html += ' <a id="tab_'+key+'" class="tab">'+key+'</a> ';
	        	});
	        	html += '</div>';
				for (var season in data) {
		        	html += '<table id = "'+season+'" class="secondary additional"><tr><td width="150">Größen</td>';
	        		for (var item in data[season]) {
			        	html += '<td><span>'+data[season][item].Groesse+'</span></td>';
	        		}

			        html += '<td><span>Summen</span></td>';
			        html += '</tr><tr><td width="150">Anzahl:</td>';

			        var sumAnzahl = 0;
	        		for (var item in data[season]) {
			        	html += '<td><span>'+data[season][item].Anzahl+'</span></td>';
			        	sumAnzahl += data[season][item].Anzahl;
			        }
			        html += '<td class="right"><span>'+sumAnzahl+'</span></td>';
					
					var sumLaden = 0;
			        html += '</tr><tr><td width="150">Laden</td>';
	        		for (var item in data[season]) {
			        	html += '<td><span>'+data[season][item].Laden+'</span></td>';
			        	sumLaden += data[season][item].Laden;
			        }
					html += '<td class="right"><span>'+sumLaden+'</span></td>';

					var sumRueckstand = 0;
			        html += '</tr><tr><td width="150">Rückstand</td>';
	        		for (var item in data[season]) {
			        	html += '<td><span>'+data[season][item].Rueckstand+'</span></td>';
			        	sumRueckstand += data[season][item].Rueckstand;
			        }
					html += '<td class="right"><span>'+sumRueckstand+'</span></td>';

					var sumOffene_Lief = 0;
			        html += '</tr><tr><td width="150">Offene Lief</td>';
	        		for (var item in data[season]) {
			        	html += '<td><span>'+data[season][item].Offene_Lief+'</span></td>';
			        	sumOffene_Lief += data[season][item].Offene_Lief;
			        }
			        html += '<td class="right"><span>'+sumOffene_Lief+'</span></td>';

			        if (sid == 'SID') {
			        	var sumOffene_Lief2 = 0;
				        html += '</tr><tr><td width="150">Offene Lief2</td>';
		        		for (var item in data[season]) {
				        	html += '<td><span>'+data[season][item].Offene_Lief2+'</span></td>';
				        	sumOffene_Lief += data[season][item].Offene_Lief2;
				        }
				        html += '<td class="right"><span>'+sumOffene_Lief2+'</span></td>';
			    	}

			        var sumFakt_aktuelle_Saison = 0;
			        html += '</tr><tr><td width="150">Sum Fakt</td>';
	        		for (var item in data[season]) {
			        	html += '<td><span>'+data[season][item].Fakt_aktuelle_Saison+'</span></td>';
			        	sumFakt_aktuelle_Saison += data[season][item].Fakt_aktuelle_Saison;
			        }
					html += '<td class="right"><span>'+sumFakt_aktuelle_Saison+'</span></td>';
			        html += '</tr>';
	        		html += '</table>';
        		}
        		html += '</div>';
        		id = result.ArtikelNummer;
        	}
        	$('#article').append(html);
        	$('#tab_'+sid).addClass('active');
        	$('#'+sid).show();
        },
        complete: function() {
        	ajaxComplete();
        	$('.close').on('click', function(){ closePopup(); });
        	$('.tab').on('click', function(){ $('.tab').removeClass('active'); $(this).addClass('active'); $('.additional').hide(); $('#'+ $(this).text()).fadeToggle(); });
        }
	})
}

function getPopup(){
	$('body').append('<div class="overlay"></div>');
	$('body').addClass('popup_opened');
}

function ajaxBefore(){
	$('.wait').show();
}

function ajaxComplete(){
	$('.wait').hide();
}

function closePopup(){
	$('#article').html('');
	$('#article').hide();
	$('.overlay').remove();
	$('body').removeClass('popup_opened');
}
