(function($) {

  // Init fSelect switchers
  var fselects = $('.facetwp-layout-switcher.type-fselect select');
  init_fselects(fselects);

  // Set initial mode
  set_initialmode();

  // Trigger switch for switcher type text or icon
  $().on('click', '.facetwp-layout-switcher li a', function(e) {
    e.preventDefault();
    var trigger = $(this);
    var target = get_targets(trigger.closest('.facetwp-layout-switcher').nodes[0]);
    var layoutmode = trigger.closest('li').nodes[0].getAttribute('data-value');
    sync_and_switch(layoutmode, target);
  });

  // Trigger switch for switcher type dropdown or fselect
  $().on('change', '.facetwp-layout-switcher select', function() {
    var trigger = $(this);
    var target = get_targets(trigger.closest('.facetwp-layout-switcher').nodes[0]);
    if ('' !== trigger.val()) {
      var layoutmode = trigger.val();
      sync_and_switch(layoutmode, target);
    }
  });

  // Sync all switchers with same target, then switch class of target(s)
  function sync_and_switch(layoutmode, target) {

    var targetclasses = '.' + target.join('.');

    if (target.length === 1) {
      targetclasses = targetclasses + ':not(.multitarget)';
    }

    // Sync active classes for type text and icons
    $(targetclasses + ' li').removeClass('active');
    $(targetclasses + ' li[data-value="' + layoutmode + '"]').addClass('active');

    // Sync "selected" for type dropdown and fselect
    $(targetclasses + ' select').each(node => node.value = layoutmode);

    // Reset fSelects and set selected class for bg color
    init_fselects(fselects);
    $(targetclasses + ' .fs-option[data-value="' + layoutmode + '"]').addClass('selected');

    switch_target_class(layoutmode, target);

  }

  // Switch class of targeted item(s)
  function switch_target_class(layoutmode, target) {

    var is_multitargetswitcher = target.length > 1;

    target.forEach((targetelement) => {
      targetelement = targetelement.replace('target-', '');
      var targetnodes = $('.' + targetelement).nodes;

      if (targetnodes.length) {
        var classnameregex = is_multitargetswitcher ? /(^|\s)multitarget-mode-\S+/g : /(^|\s)singletarget-mode-\S+/g;

        for (var i = 0; i < targetnodes.length; i++) {
          targetnodes[i].className = targetnodes[i].className.replace(classnameregex, '');
          $(targetnodes[i]).addClass(layoutmode);
        }
      }
    });

  }

  // Get targeted item(s)
  function get_targets(switcher) {
    var switcherclasses = switcher.classList;
    var target = [];
    if (switcherclasses.length > 0) {
      for (var i = 0; i < switcherclasses.length; i++) {
        var targetclass = switcherclasses[i];
        if (targetclass.startsWith('target-')) {
          target.push(targetclass);
        }
      }
    }
    return target;
  }

  // Get initial mode
  function get_initialmode(switcher) {
    var switcherclasses = switcher.classList;
    for (var i = 0; i < switcherclasses.length; i++) {
      var initialclass = switcherclasses[i];
      if (initialclass.startsWith('initial-')) {
        return initialclass.replace('initial-', '');
      }
    }
    return null;
  }

  // Find the first switcher and run the switcher sync and target class switch if it is not set to setinitial-false.
  // Runs separately for singletarget and multitarget switchers so both can be used together and have separate initial settings.
  function set_initialmode() {
    var switcherTypes = [
      { type: 'singletarget', prefix: 'singletarget-mode-' },
      { type: 'multitarget', prefix: 'multitarget-mode-' }
    ];

    switcherTypes.forEach(({ type, prefix }) => {
      var switchers = $(`.facetwp-layout-switcher.${type}`).nodes;
      if (switchers.length > 0) {
        if (!switchers[0].classList.contains('setinitial-false')) {
          var initialmode = get_initialmode(switchers[0]);
          if (initialmode) {
            var target = get_targets(switchers[0]);
            sync_and_switch(prefix + initialmode, target);
          }
        }
      }
    });
  }

  // (Re)init fSelect switchers
  function init_fselects(fselects) {
    fselects.each(node => fSelect(node, {'showSearch': false}));
  }

})(fUtil);