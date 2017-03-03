/*
* Tento skript vyhleda hlavicku tabulky, zkopiruje jeji obsah a sirku jednotlivych
* sloupcu. Tak vznikne nova tabulka s hlavicku, ktera je pouzita jako fixni prvek
* na strance.
* Puvodni skript byl navic s funkci zobrazeni/skryti hlavicky pri posunu (scroll)
* stranky.
*/

/*$(document).ready(function() {
  var tableOffset = $(".table-padding-content").offset().top;
  var $header = $(".table-padding-content > thead");
  var $fixedHeader = $("#header-fixed").append($header.clone());
  
  $(window).bind("scroll", function() {
      var offset = $(this).scrollTop();
      
      if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
          $fixedHeader.css( "display", "block")
          
          $.each($header.find('tr > th'), function(ind,val){
            var original_width = $(val).width();
            $($fixedHeader.find('tr > th')[ind]).width(original_width);
          });
      }
      else if (offset < tableOffset) {
          $fixedHeader.hide();
      }
  });
}); */

$(document).ready(function() {
/*
* Nastavuje sirku prekryvne tabulky, ktera kopiruje hlavicku hlavni tabulky a je
* zobrazena fixne pri posuvu obsahu okna
* Vstupni parametry jsou selector na hlavicku hlavni tabulky a selector na hlavicku
* prekryvne (fixni) tabulky
*/
  function change_width(header, fixedHeader) {    
    $.each(header.find('tr > th'), function(ind,val){
      //var original_width = $(val).width();  //zaokrouhluje na cela cisla, muze byt videt uskok
      var original_width = window.getComputedStyle(val).width;
      $(fixedHeader.find('tr > th')[ind]).width(original_width);  
    });
  }
  var $header = $("#table-main > thead");
  var $fixedHeader = $("#table-header-fixed").prepend($header.clone());
  //var $fixedHeader = $("#table-header-fixed > thead");
  change_width($header, $fixedHeader);
  
/*
* Zmena sirky je potreba i pri zmene velikosti okna, bude provedena je pri dokonceni
* zmeny okna - nezatezuje tolik prepocitavanim sirek prohlizec
*/
  var rtime;
  var timeout = false;
  var delta = 200;
  $(window).resize(function() {
    rtime = new Date();
    if (timeout === false) {
      timeout = true;
      setTimeout(resize_end, delta);
    }   
  });
  
  function resize_end() {
    if (new Date() - rtime < delta) {
      setTimeout(resize_end, delta);
    } else {
      timeout = false;
      change_width($header, $fixedHeader);
    }
  } 

/*
* Dynamicke vyhledavani v tabulce a zobrazovani jednotlivych radku
*/
  var $rows = $('#table-main tbody tr');
  $('#search').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    var rowCount = sumVet;
    
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        if (!~text.indexOf(val)) {
          rowCount--;
        }
        return !~text.indexOf(val);
    }).hide();
    
    /*
    * Zobrazeni poctu zaznamu po odfiltrovani
    */
    $("#row-counter #filtered").html(rowCount);
    /* pri vyhledavani se defaultne meni sirka sloupcu, proto je potreba menit i sirku
    * fixni hlavicky v tabulce fixed-table
    */  
    change_width($header, $fixedHeader);
    /*
    * Pri vyhledavani mohou byt prvni vysledky skryty za fixni tabulkou,
    * pokud uzivatel prohlizi behem zadavani hledaneho retezce vysledky,
    * proto je potreba donutit obsah okna posunout nahoru
    */
    $('html, body').animate({scrollTop : 0},800);
  });
  
  /*
  * Zobrazi stin zespodu hlavicky, pokud je stranka posunuta a nejake radky jsou
  * skryty za hlavickou
  */
  $(window).scroll(function(){
		if ($(this).scrollTop() > 5) {
			$("#table-header-fixed thead > tr").css("box-shadow", "0px 2px 3px rgba(16, 240, 168, 0.6)");
    } else {
      $("#table-header-fixed thead > tr").css("box-shadow", "");
    }
	});
  
/*
* Sipka nahoru, ktera se zobrazi pri posunu stranky v pravem dolnim rohu
*/
  var rowNr = 200;
  $(window).scroll(function() {
  	if ( $(window).scrollTop() > rowNr ) {
  		$("#back-to-top").fadeIn("slow");
  	} else {
  		$("#back-to-top").fadeOut("slow");
  	}
  }); 
  $("#back-to-top").click(function() {
    $("html, body").animate({
		  scrollTop: 0
  	}, 700);
  	return false;  
  });
  
/*
* Kopirovani textu s SQL
*/  
  var copySqlBtn = document.querySelector('#sql-copy');  
  copySqlBtn.addEventListener('click', function(event) {  
  // Select the email link anchor text  
  var sqlCode = document.querySelector('#sql-code');  
  var range = document.createRange();  
  range.selectNode(sqlCode);  
  window.getSelection().addRange(range);

  try {  
    // Now that we've selected the anchor text, execute the copy command  
    var successful = document.execCommand('copy');  
    var msg = successful ? 'úspěšné' : 'neúspěšné';  
    console.log('Kopírování SQL dotazu bylo ' + msg);
    $("#copy-info").fadeIn(300).delay(600).fadeOut(300);  
  } catch(err) {  
    console.log('Nelze zkopírovat SQL');
    $("#copy-info").html("Chyba").fadeIn(300).delay(600).fadeOut(300);  
  }

  // Remove the selections - NOTE: Should use
  // removeRange(range) when it is supported  
  window.getSelection().removeAllRanges();  
  });
});