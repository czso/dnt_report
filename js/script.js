$(document).ready(function () {
  var tableRows = $('#table-main tbody tr');
  var rowSum = tableRows.length;
  $("#row-counter #all").html(rowSum);
  var filteredRows = $("#row-counter #filtered");
  /*
   * Zobrazi stin zespodu hlavicky, pokud je stranka posunuta a nejake radky jsou
   * skryty za hlavickou
   */
  $(window).scroll(function () {
    if ($(this).scrollTop() > 5) {
      $(".tablesorter-sticky-wrapper").css("box-shadow", "0px 2px 3px rgba(16, 240, 168, 0.6)");
    } else {
      $(".tablesorter-sticky-wrapper").css("box-shadow", "");
    }
  });

  /*
   * Sipka nahoru, ktera se zobrazi pri posunu stranky v pravem dolnim rohu
   */
  var rowNr = 200;
  $(window).scroll(function () {
    if ($(window).scrollTop() > rowNr) {
      $("#back-to-top").fadeIn("slow");
    } else {
      $("#back-to-top").fadeOut("slow");
    }
  });
  $("#back-to-top").click(function () {
    $("html, body").animate({
      scrollTop: 0
    }, 700);
    return false;
  });

  /*
   * Kopirovani textu s SQL
   */
  var copySqlBtn = document.querySelector('#sql-copy');
  if (copySqlBtn !== null) {
    copySqlBtn.addEventListener('click', function (event) {
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
        $("#copy-info").html("Zkopírováno do schránky")
                .removeClass().addClass( "success" )
                .fadeIn(300).delay(1500).fadeOut(300);
        window.getSelection().removeAllRanges();
      } catch (err) {
        console.log('Nelze zkopírovat SQL:' + err);
        $("#copy-info").html("Chyba - zkopírujte ručně (Ctrl+C)")
                .removeClass().addClass( "error" )
                .fadeIn(300).delay(1500).fadeOut(300);
      }

      // Remove the selections - NOTE: Should use
      // removeRange(range) when it is supported  
      //window.getSelection().removeAllRanges();
    });
  }

  /*
   * Dynamicke vyhledavani v tabulce a zobrazovani jednotlivych radku
   */
  $('#search').keyup(function () {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    var rowCount = rowSum;

    tableRows.show().filter(function () {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      if (!~text.indexOf(val)) {
        rowCount--;
      }
      return !~text.indexOf(val);
    }).hide();

    /*
     * Zobrazeni poctu zaznamu po odfiltrovani
     */
    filteredRows.html(rowCount);
    /*
     * Pri vyhledavani mohou byt prvni vysledky skryty za fixni tabulkou,
     * pokud uzivatel prohlizi behem zadavani hledaneho retezce vysledky,
     * proto je potreba donutit obsah okna posunout nahoru
     */
    $('html, body').animate({scrollTop: 0}, 800);
  });


  /*
   * Trideni zaznamu v tabulce
   */
  $(".table-sorter").tablesorter({
    widthFixed: true,
    //showProcessing: true,
    //headerTemplate : '{content} {icon}', // Add icon for various themes

    widgets: ['zebra', 'stickyHeaders', 'filter'],

    widgetOptions: {

      // extra class name added to the sticky header row
      stickyHeaders: '',
      // number or jquery selector targeting the position:fixed element
      stickyHeaders_offset: 70,
      // added to table ID, if it exists
      stickyHeaders_cloneId: '-sticky',
      // trigger "resize" event on headers
      stickyHeaders_addResizeEvent: true,
      // if false and a caption exist, it won't be included in the sticky header
      stickyHeaders_includeCaption: false,
      // The zIndex of the stickyHeaders, allows the user to adjust this to their needs
      stickyHeaders_zIndex: 1,
      // jQuery selector or object to phycially attach the sticky headers
      stickyHeaders_appendTo: false,
      // jQuery selector or object to attach scroll listener to (overridden by xScroll & yScroll settings)
      stickyHeaders_attachTo: null,
      // jQuery selector or object to monitor horizontal scroll position (defaults: xScroll > attachTo > window)
      stickyHeaders_xScroll: null,
      // jQuery selector or object to monitor vertical scroll position (defaults: yScroll > attachTo > window)
      stickyHeaders_yScroll: null,
      // scroll table top into view after filtering
      stickyHeaders_filteredToTop: true,

      // jQuery selector string of an element used to reset the filters
      filter_reset: $("#reset-filter")

              // *** REMOVED jQuery UI theme due to adding an accordion on this demo page ***
              // adding zebra striping, using content and default styles - the ui css removes the background from default
              // even and odd class names included for this demo to allow switching themes
              // , zebra   : ["ui-widget-content even", "ui-state-default odd"]
              // use uitheme widget to apply defauly jquery ui (jui) class names
              // see the uitheme demo for more details on how to change the class names
              // , uitheme : 'jui'
    }
  }).bind('filterEnd', function (event, config) {
    var rowCount = $("#table-main > tbody > tr:not(.filtered)").length;
    $("#row-counter #filtered").html(rowCount);
  });

  /*
   * Zapnuti / vypnuti filtru a jeho reset
   */
  var inpSearch = $("#search");
  $("#filter").click(function () {
    $(".table-sorter .tablesorter-filter-row").toggle();
    if ($(this).html() === 'Zapnout') {
      $(this).html('Vypnout');
      $("#reset-filter").show();
      inpSearch.prop('disabled', true)
              .val('')
              .attr('placeholder', "Nejprve vypněte Filtr v menu");
      tableRows.css('display', '');
      filteredRows.html(rowSum);      
      $(".table-sorter").trigger('filterReset');
    } else {
      $(this).html('Zapnout');
      $("#reset-filter").hide();
      inpSearch.prop('disabled', false)
              .attr('placeholder', "Začněte psát...");
      $(".table-sorter").trigger('filterReset');
      return false;
    }
  });
});