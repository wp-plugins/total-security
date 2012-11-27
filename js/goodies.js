window.goodies = {};
jQuery.fn.rangeInput = function(range, opts) {
  return this.each(function() {
    var $this = $(this);
    var bottom = range[0],
        top = range[1];
    var $up = $('<button class="btn btn-m rpp-btn">&#9650;</button>');
    var $down = $('<button class="btn btn-m rpp-btn">&#9660;</button>');
    var $input = $('<input disabled id="' + opts.id + '" type="text" class="range-input text" size="3" maxlength="3" value="' + opts.def + '">');
    var timerUp, timerDown, mousedownUp, mousedownDown;

    $input.focus(function() {
      $(this).blur();
    });
    $up.click(function(e) {
      e.preventDefault();
    });
    $down.click(function(e) {
      e.preventDefault();
    });

    var $container = $('<div class="range-container"/>');
    var $arrows = $('<div class="range-arrows"/>');
    $arrows.append($up);
    $arrows.append($down);
    $container.append($input);
    $container.append($arrows);
    $this.append($container);

    var mousedown = function(direction) {
      function decrease() {
        $input.val(parseInt($input.val()) - 1);
      }
      function increase() {
        $input.val(parseInt($input.val()) + 1);
      }
      switch (direction) {
        case 'down':
          if (parseInt($input.val()) > bottom) {
            decrease();
          }
          break;
        case 'up':
          if (parseInt($input.val()) < top) {
            increase();
          }
          break;
      }
    };
    $(window).mouseup(function() {
      if (timerUp) {
        clearInterval(timerUp);
      }
      if (mousedownUp) {
        clearTimeout(mousedownUp);
      }
      if (timerDown) {
        clearInterval(timerDown);
      }
      if (mousedownDown) {
        clearTimeout(mousedownDown);
      }
    });
    $up.mousedown(function(e) {
      e.preventDefault();
      mousedown('up');
      timerUp = setTimeout(function() {
        mousedownUp = setInterval(mousedown, 50, 'up');
      }, 500);
    });
    $down.mousedown(function(e) {
      e.preventDefault();
      mousedown('down');
      timerDown = setTimeout(function() {
        mousedownDown = setInterval(mousedown, 50, 'down');
      }, 500);
    });

  });
};

$.fn.blink = function() {
  return this.each(function(el) {
    var $this = $(this);
    function fadeIn(cb) {
      $this.fadeIn('500', cb);
    }
    function fadeOut(cb) {
      $this.fadeOut('500', cb);
    }
    fadeOut(fadeIn);
  });
};

$.fn.mirror = function(selector) {
  return this.each(function() {
    var $this = $(this);
    var $selector = $(selector);
    $this.bind('keyup', function() {
      $selector.html(h($this.val()));
    });
  });
};

// placeholders. overridden on each widget page
window.testSettings = function() { };
window.getWidgetCode = function() { };

$('.widget-colors').ColorPicker(function() {
  var $input;
  var changed = false;
  var vals = [];
  var hidden = true;
  return {

    onSubmit: function(hsb, hex, rgb, el) {
      if (hidden) {
        return;
      }
      hidden = true;
      $(el).val('#' + hex);
      $(el).ColorPickerHide();
      var rel = $(el).attr('rel');
      var theme = goodies.getWidgetTheme();
      DemoWidget.setTheme(theme, true);
      DemoWidget.resume();
    },

    onShow: function(colpkr) {
      $input = $(this);
      hidden = false;
      changed = false;
    },

    onBeforeShow: function() {
      $(this).ColorPickerSetColor(this.value);
      DemoWidget.pause();
    },

    onChange: function(hsb, hex, rgb, el) {
      changed = true;
      vals = [hsb, hex, rgb, $input];
      $input.next().css('background-color', '#' + hex);
      var rel = $input.attr('rel');
      switch (rel) {
        case 'shell-bg':
          $('#example-preview-widget .twtr-doc, \
             #example-preview-widget .twtr-hd a, \
             #example-preview-widget .twtr-ft a,  \
             #example-preview-widget h5, #example-preview-widget h4, \
             #example-preview-widget .twtr-popular')
            .css('background-color', '#' + hex);
          $('#example-preview-widget .twtr-popular').css('background-color', '\
              rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',.3)');
        break;
        case 'shell-color':
          $('#example-preview-widget .twtr-doc, \
             #example-preview-widget .twtr-hd a, \
             #example-preview-widget .twtr-ft a, \
             #example-preview-widget h5, #example-preview-widget h4')
            .css('color', '#' + hex);
        break;
        case 'tweet-background':
          $('#example-preview-widget .twtr-timeline, #example-preview-widget .twtr-tweet')
            .css('background-color', '#' + hex);
        break;
        case 'tweet-links':
          $('#example-preview-widget .twtr-timeline a').filter(':not(em a)')
            .css('color', '#' + hex);
        break;
        case 'tweet-color':
          $('#example-preview-widget .twtr-bd em a, #example-preview-widget .twtr-timeline, \
             #example-preview-widget .twtr-bd p, \
             #example-preview-widget .twtr-popular')
            .css('color', '#' + hex);
        break;
      }
    },

    onHide: function(colpkr, hex) {
      if (changed && !hidden) {
        this.onSubmit.apply(this, vals);
      }
      changed = false;
    }
  };
}());



$('#sw-tab-panes form:not(.get-widget-code)').submit(function(e) {
  e.preventDefault();
});

$('.widget-colors').each(function() {
  var $this = $(this);
  var val = $this.val();
  $(this).next().css('background-color', val);
});



$('#sw-widget-interval-container').rangeInput([30, 60], {
  id: 'sw-widget-interval',
  def: 30
});

$('#sw-widget-caption').bind('click focus', function() {
  $('#example-preview-widget h4').blink();
});
$('#sw-widget-title').bind('click focus', function() {
  $('#example-preview-widget h5').blink();
});


$('#sw-widget-behavior-default').click(function() {
  $('#sw-widget-loop-yes').removeAttr('disabled');
  $('#interval-group').removeClass('disabled-group');
});
$('#sw-widget-behavior-all').click(function() {
  $('#interval-group').addClass('disabled-group');
  $('#sw-widget-loop-yes').attr('disabled', 'disabled');
});
$('ul.tabset li').hover(
  function() { $(this).addClass('hover'); },
  function() { $(this).removeClass('hover'); }
);



$('#sw-test-settings').bind('click', function(e) {
  testSettings(e);
});
$('form#sw-settings-form').submit(function(e) {
  testSettings(e);
});
$('#widget-finish').bind('click', function(e) {
  e.preventDefault();
  $('#widget-code-src').val(getWidgetCode());
});

$('#widget-code-src').bind('click', function(e) {
  if ($.browser.msie) {
    var r = this.createTextRange();
    r.select();
    r.execCommand('copy');
  } else {
    this.select();
  }
});

$('#sw-appearance b').bind('click', function(e) {
  $(this).prev().click();
});

(function() {
  var $tabs = $('ul.tabset li, #widget-finish');
  var items = $tabs.map(function(el) {
    return $('#' + $(this).attr('rel')).get(0);
  });
  $tabs.bind('click', function(e) {
    e.preventDefault();
    $tabs.removeClass('current');
    $(items).removeClass('active');
    var $this = $(this);
    $('#' + $this.attr('rel')).css('display', 'none').addClass('active').fadeIn('slow');
    $this.addClass('current');
  });
})();

goodies.getWidgetTheme = function() {
  return {
    shell: {
      background: $('#sw-shell-background').val(),
      color: $('#sw-shell-color').val()
    },
    tweets: {
      background: $('#sw-tweet-background').val(),
      color: $('#sw-tweet-text').val(),
      links: $('#sw-tweet-links').val()
    }
  };
};
goodies.getWidgetOptions = function() {
  function getWidth() {
    return ($('#sw-auto-width:checked').length) ? "'auto'" : $('#widget-width-value').val();
  }
  return {
    loop: $('input#sw-widget-loop-yes:checked:enabled').length ? true : false,
    interval: parseInt($('input#sw-widget-interval').val()) * 1000,
    live: $('input#sw-widget-live-yes:checked').length ? true : false,
    scrollbar: $('input#sw-widget-scrollbar-yes:checked').length ? true : false,
    behavior: $('input#sw-widget-behavior-default:checked').length ? 'default' : 'all',
    rpp: $('#rpp-input').val(),
    width: getWidth(),
    height: $('#widget-height-value').val()
  };
};