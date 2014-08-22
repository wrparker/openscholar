/**
 * Saves the current active tab for the session.
 * Restores this tab as the active tab when we return.
 */

Drupal.behaviors.os_boxes_tabs = { attach: function (ctx) {
  var $ = jQuery;

  // without this, the contextual links for a node are treated as tabs
  // thus, it loads crap like the edit form
  // which loads the cp theme and causes css weirdness
  $.widget('os_boxes.tabsBox', $.ui.tabs, {
    _getList: function() {
      var list = this.element.find('.tab-links');
      return list.eq(0);
    }
  });

  $('.block-boxes-os_boxes_tabs', ctx).once('tabs', function () {
    // filter out children .block-content elements so we don't make them into tabs too
    // if they are a tab widget (nesting tabs, ugh), then we'll handle them in a separate loop iteration
    $('.block-content', this).not(this.id + ' .block-content .block-content').tabsBox({
      show: clickHandle,
      selected: (typeof window.sessionStorage != 'undefined' && typeof sessionStorage[this.id] != 'undefined')?sessionStorage[this.id]:0
    });
  });

  function clickHandle(e) {
    var $this = $(this),
      id = $this.parents('.block').attr('id'),
      val = $(this).tabs('option', 'selected');

    if (typeof window.sessionStorage != 'undefined')
      window.sessionStorage[id] = val;
  }
}};