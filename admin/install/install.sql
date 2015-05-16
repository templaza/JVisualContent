CREATE TABLE IF NOT EXISTS `#__tz_jvisualcontent_extrafields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `option_value` TEXT,
  `css_code` TEXT NOT NULL,
  `protected` TINYINT NOT NULL,
  `ordering` INT NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__tz_jvisualcontent_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `class_icon` varchar(255) NOT NULL,
  `image_icon` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `protected` tinyint(4) NOT NULL,
  `fields_id` VARCHAR(255) NOT NULL,
  `html` text NOT NULL,
  `css_code` text NOT NULL,
  `js_code` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `introtext` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__tz_jvisualcontent_extrafields` (`id`, `name`, `title`, `published`, `type`, `option_value`, `css_code`, `protected`, `ordering`, `description`) VALUES
(1, 'title', 'Title', 1, 'textfield', '{"type":"text"}', '', 0, 0, ''),
(2, 'button_type', 'Button Type', 1, 'select', '{"title":["Default","Primary","Success","Info","Warning","Danger","Link"],"value":["jvc_btn-default","jvc_btn-primary","jvc_btn-success","jvc_btn-info","jvc_btn-warning","jvc_btn-danger","jvc_btn-link"]}', '', 0, 0, ''),
(3, 'button_size', 'Button Size', 1, 'select', '{"title":["Default","Large","Small","Extra small"],"value":["","jvc_btn-lg","jvc_btn-sm","jvc_btn-xs"]}', '', 0, 0, ''),
(4, 'button_block', 'Button Block', 1, 'checkbox', '{"title":["Yes"],"value":["jvc_btn-block"]}', '', 0, 0, ''),
(5, 'disabled', 'Disabled', 1, 'checkbox', '{"title":["Yes"],"value":["jvc_disabled"]}', '', 0, 0, ''),
(6, 'active', 'Active', 1, 'checkbox', '{"title":["Yes"],"value":["jvc_active"]}', '', 0, 0, ''),
(7, 'image_url', 'Image Url', 1, 'image', '{}', '', 0, 0, ''),
(8, 'image_caption', 'Image Caption', 1, 'textfield', '{"type":"text"}', '', 0, 0, ''),
(9, 'image_shape', 'Image Shape', 1, 'select', '{"title":["Default","Rounded","Circle","Thumbnail"],"value":["","jvc_img-rounded","jvc_img-circle","jvc_img-thumbnail"]}', '', 0, 0, ''),
(10, 'editor', 'Editor', 1, 'editor', '{}', '', 0, 0, ''),
(11, 'alert_type', 'Alert Type', 1, 'select', '{"title":["Default","Success","Info","Warning","Danger"],"value":["","jvc_alert-success","jvc_alert-info","jvc_alert-warning","jvc_alert-danger"]}', '', 0, 0, ''),
(12, 'progress_bar_percent', 'Progress Bar Percent', 1, 'textfield', '{"type":"number"}', '', 0, 0, ''),
(13, 'progress_bar_min', 'Progress Bar Min', 1, 'textfield', '{"type":"number"}', '', 0, 0, ''),
(14, 'progress_bar_max', 'Progress Bar Max', 1, 'textfield', '{"type":"number"}', '', 0, 0, ''),
(15, 'progress_bar_striped', 'Progress Bar Striped', 1, 'checkbox', '{"title":["Yes"],"value":["jvc_progress-bar-striped"]}', '', 0, 0, ''),
(16, 'progress_bar_type', 'Progress Bar Type', 1, 'select', '{"title":["Default","Success","Info","Warning","Danger"],"value":["","jvc_progress-bar-success","jvc_progress-bar-info","jvc_progress-bar-warning","jvc_progress-bar-danger"]}', '', 0, 0, ''),
(17, 'carousel_item_number', 'Carousel Item Number', 1, 'textfield', '{"type":"number"}', '', 0, 0, 'Start from number 0'),
(18, 'panel_type', 'Panel Type', 1, 'select', '{"title":["Default","Primary","Success","Info","Warning","Danger"],"value":["jvc_panel-default","jvc_panel-primary","jvc_panel-success","jvc_panel-info","jvc_panel-warning","jvc_panel-danger"]}', '', 0, 0, 'A heading container to your panel'),
(19, 'panel_active', 'Panel Active', 1, 'checkbox', '{"title":["Yes"],"value":["jvc_in"]}', '', 0, 0, ''),
(20, 'popover_title', 'Popover Title', 1, 'textfield', '{"type":"text"}', '', 0, 0, ''),
(21, 'popover_content', 'Popover Content', 1, 'textarea', '{}', '', 0, 0, ''),
(22, 'popover_placement', 'Popover Placement', 1, 'select', '{"title":["Top","Right","Bottom","Left"],"value":["top","right","bottom","left"]}', '', 0, 0, ''),
(23, 'label_type', 'Label Type', 1, 'select', '{"title":["Default","Primary","Success","Info","Warning","Danger"],"value":["jvc_label-default","jvc_label-primary","jvc_label-success","jvc_label-info","jvc_label-warning","jvc_label-danger"]}', '', 0, 0, ''),
(24, 'quote_reverse', 'Quote Reverse', 1, 'checkbox', '{"title":["Yes"],"value":["jvc_blockquote-reverse"]}', '', 0, 0, 'Add .jvc_blockquote-reverse for a blockquote with right-aligned content.');


INSERT IGNORE INTO `#__tz_jvisualcontent_elements` (`id`, `name`, `title`, `class_icon`, `image_icon`, `published`, `protected`, `fields_id`, `html`, `css_code`, `js_code`, `ordering`, `introtext`) VALUES
(1, 'button', 'Button', 'jvc_fa jvc_fa-link', '', 1, 0, '', '<button class="jvc_btn {button_type} {button_size} {button_block} {disabled} {active}" type="submit">{title}</button>', '', '', 0, 'Eye catching button'),
(2, 'single_image', 'Single Image', 'jvc_fa jvc_fa-picture-o', '', 1, 0, '', '<img src="{image_url}" alt="{image_caption}" class="{image_shape}"/>', '', '', 0, 'Single image with shapes'),
(3, 'text_block', 'Text Block', 'jvc_fa jvc_fa-font', '', 1, 0, '', '{editor}', '', '', 0, 'A block of text with WYSIWYG editor'),
(4, 'alert', 'Alert', 'jvc_fa jvc_fa-info-circle', '', 1, 0, '', '<div class="jvc_alert {alert_type}" role="alert">{editor}</div>', '', '', 0, 'Notification box'),
(6, 'tab', 'Tab', 'jvc_fa jvc_fa-folder-o', '', 1, 0, '', '<div role="tabpanel">\r\n\r\n  <ul class="jvc_nav jvc_nav-tabs" role="tablist">\r\n    [loop]<li role="presentation" class="{active}"><a href="#[/typeid]" aria-controls="[/typeid]" role="tab" data-toggle="jvc_tab">{title}</a></li>[/loop]\r\n  </ul>\r\n\r\n  <div class="jvc_tab-content">\r\n    [loop]<div role="tabpanel" class="jvc_tab-pane {active}" id="[/typeid]">[/type]</div>[/loop]\r\n  </div>\r\n\r\n</div>', '', '', 0, 'Tabbed content'),
(5, 'accordion', 'Accordion', 'jvc_fa jvc_fa-bars', '', 1, 0, '', '<div class="jvc_panel-group" id="[/parentid]" role="tablist" aria-multiselectable="true">\r\n[loop]\r\n  <div class="jvc_panel {panel_type}">\r\n    <div class="jvc_panel-heading" role="tab" id="[/typeid]">\r\n      <h4 class="jvc_panel-title">\r\n        <a data-toggle="jvc_collapse" data-parent="#[/parentid]" href="#[/typeid2]" aria-expanded="true" aria-controls="[/typeid2]">\r\n          {title}\r\n        </a>\r\n      </h4>\r\n    </div>\r\n    <div id="[/typeid2]" class="jvc_panel-collapse jvc_collapse {panel_active}" role="tabpanel" aria-labelledby="[/typeid]">\r\n      <div class="jvc_panel-body">\r\n        [/type]\r\n      </div>\r\n    </div>\r\n  </div>\r\n[/loop]\r\n</div>', '', '', 0, 'Collapsible content panels'),
(7, 'progress_bar', 'Progress Bar', 'jvc_fa jvc_fa-align-left', '', 1, 0, '', '<div class="jvc_progress">\r\n  <div class="jvc_progress-bar {progress_bar_type} {progress_bar_striped} {active} {progressbar_striped}" role="progressbar" aria-valuenow="{progress_bar_percent}" aria-valuemin="{progress_bar_min}" aria-valuemax="{progress_bar_max}" style="width: {progress_bar_percent}%;">\r\n    {editor}\r\n  </div>\r\n</div>', '', '', 0, 'Progress bar not animate'),
(8, 'image_carousel', 'Image Carousel', 'jvc_fa jvc_fa-file-image-o', '', 1, 0, '', '<div id="[/parentid]" class="jvc_carousel jvc_slide" data-ride="jvc_carousel">\r\n\r\n  <ol class="jvc_carousel-indicators">\r\n    [loop]<li data-target="#[/parentid]" data-jvc_slide-to="{carousel_item_number}" class="{active}"></li>[/loop]\r\n  </ol>\r\n\r\n  <div class="jvc_carousel-inner" role="listbox">\r\n    [loop]<div class="jvc_item {active}">\r\n      [/type]\r\n      </div>[/loop]\r\n  </div>\r\n\r\n  <a class="jvc_left jvc_carousel-control" href="#[/parentid]" role="button" data-jvc_slide="prev">\r\n    <span class="jvc_glyphicon jvc_glyphicon-chevron-left" aria-hidden="true"></span>\r\n    <span class="jvc_sr-only">Previous</span>\r\n  </a>\r\n  <a class="jvc_right jvc_carousel-control" href="#[/parentid]" role="button" data-jvc_slide="next">\r\n    <span class="jvc_glyphicon jvc_glyphicon-chevron-right" aria-hidden="true"></span>\r\n    <span class="jvc_sr-only">Next</span>\r\n  </a>\r\n</div>', '', '', 0, 'Animated carousel with images'),
(9, 'popover', 'Popover', 'jvc_glyphicon jvc_glyphicon-comment', '', 1, 0, '', '<button type="button" class="jvc_btn {button_type} {button_size} {button_block}" data-container="body" title="{popover_title}" data-toggle="jvc_popover" data-placement="{popover_placement}" data-content="{popover_content}">\r\n  {title}\r\n</button>', '', '', 0, 'Add small overlays of content'),
(10, 'label', 'Label', 'jvc_fa jvc_fa-tag', '', 1, 0, '', '<span class="jvc_label {label_type}">{title}</span>', '', '', 0, 'Label with title'),
(11, 'blockquote', 'Blockquote', 'jvc_fa jvc_fa-quote-right', '', 1, 0, '', '<blockquote class="{quote_reverse}">\r\n  {editor}\r\n</blockquote>', '', '', 0, 'Quoting blocks of content');

