(function($) {

  // Init fSelect switchers
  var fselects = $('.facetwp-layout-switcher.type-fselect select');
  init_fselects(fselects);

  // Trigger switch for switcher type text or icon
  $().on('click', '.facetwp-layout-switcher li a', function(e) {
    e.preventDefault();
    var trigger = $(this);
    var target = get_target(trigger);
    var layoutmode = trigger.closest('li').nodes[0].getAttribute('data-value');
    sync_and_switch(layoutmode, target);

  });

  // Trigger switch for switcher type dropdown or fselect
  $().on('change', '.facetwp-layout-switcher select', function() {
    var trigger = $(this);
    var target = get_target(trigger);
    if ('' !== trigger.val()) {
      var layoutmode = trigger.val();
      sync_and_switch(layoutmode, target);
    }

  });

  // Get targeted listing
  function get_target(trigger) {
    var switcherclasses = trigger.closest('.facetwp-layout-switcher').nodes[0].classList;
    var target;
    if (switcherclasses.length > 0) {
      for (var i = 0; i < switcherclasses.length; i++) {
        var targetclass = switcherclasses[i];
        if (targetclass.startsWith('target-')) {
          target = targetclass.replace('target-', '');
        } else {
          target = 'facetwp-template';
        }
      }
    }

    return target;
  }

  // Sync all switchers with same target and switch listing class
  function sync_and_switch(layoutmode, target) {

    // Sync active classes for type text and icons
    $('.target-' + target + ' li').removeClass('active');
    $('.target-' + target + ' li[data-value="' + layoutmode + '"]').addClass('active');

    // Sync "selected" for type dropdown and fselect
    $('.target-' + target + ' select').each(node => node.value = layoutmode);

    // Reset fSelects and set selected class for bg color
    init_fselects(fselects);
    $('.target-' + target + ' .fs-option[data-value="' + layoutmode + '"]').addClass('selected');

    switch_layout_class(layoutmode, target);

  }

  // Switch targeted listing class
  function switch_layout_class(layoutmode, target) {
    target = $('.' + target);
    target.nodes[0].className = target.nodes[0].className.replace(/(^|\s)layoutmode-\S+/g, '');
    target.addClass(layoutmode);

  }

  // (Re)init fSelect switchers
  function init_fselects(fselects) {
    fselects.each(node => fSelect(node, {'showSearch': false}));

  }

})(fUtil);