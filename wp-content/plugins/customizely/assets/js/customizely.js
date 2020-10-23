/*global jQuery, _ */
(function (exports, $, win) {
  var api = exports.customize;
  var __ = exports.i18n.__;
  win.customizely = {};
  var customizely = win.customizely;
  var CSSViewer = win.customizely.CSSViewer;
  var checkWork = {
    '=': function _(a, b) {
      // eslint-disable-next-line eqeqeq
      return a == b;
    },
    '!=': function _(a, b) {
      // eslint-disable-next-line eqeqeq
      return a != b;
    },
    '==': function _(a, b) {
      return a === b;
    },
    '!==': function _(a, b) {
      return a !== b;
    },
    '>': function _(a, b) {
      return a > b;
    }
  };
  CSSViewer = api.Class.extend({
    rules: {},
    sheets: {
      desktop: null,
      laptop: null,
      tablet: null,
      'mobile-landscape': null,
      mobile: null
    },
    initialize: function initialize(elWin, elDoc) {
      var reinit = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
      this.win = elWin;
      this.doc = elDoc;

      if (reinit) {
        this.rules = {};
        this.sheets = {
          desktop: null,
          laptop: null,
          tablet: null,
          'mobile-landscape': null,
          mobile: null
        };
      }

      this.initStyleTag();
    },
    initStyleTag: function initStyleTag() {
      this.style = this.doc.createElement('style');
      this.styleLaptop = this.doc.createElement('style');
      this.styleTablet = this.doc.createElement('style');
      this.styleMobileLand = this.doc.createElement('style');
      this.styleMobile = this.doc.createElement('style');
      this.style.id = 'customizely-desktop';
      this.styleLaptop.id = 'customizely-laptop';
      this.styleTablet.id = 'customizely-tablet';
      this.styleMobileLand.id = 'customizely-mobile-landscape';
      this.styleMobile.id = 'customizely-mobile';
      this.styleLaptop.media = '(min-width: 992px) and (max-width: 1199.98px)';
      this.styleTablet.media = '(min-width: 768px) and (max-width: 991.98px)';
      this.styleMobileLand.media = '(min-width: 576px) and (max-width: 767.98px)';
      this.styleMobile.media = '(max-width: 575.98px)';
      this.doc.head.appendChild(this.style);
      this.doc.head.appendChild(this.styleLaptop);
      this.doc.head.appendChild(this.styleTablet);
      this.doc.head.appendChild(this.styleMobileLand);
      this.doc.head.appendChild(this.styleMobile);
      this.sheets = {
        desktop: this.style.sheet,
        laptop: this.styleLaptop.sheet,
        tablet: this.styleTablet.sheet,
        'mobile-landscape': this.styleMobileLand.sheet,
        mobile: this.styleMobile.sheet
      };
    },
    addRule: function addRule(sheet, device, selector) {
      if (this.rules[device] === undefined) {
        this.rules[device] = {};
      }

      this.rules[device][selector] = sheet.cssRules.length;
      sheet.insertRule(selector + '{}', sheet.cssRules.length);
      return this.rules[device][selector];
    },
    getRuleIndex: function getRuleIndex(device, selector) {
      if (this.rules[device]) {
        return this.rules[device][selector] || null;
      }

      return null;
    },
    getRule: function getRule(sheet, ruleIndex) {
      return sheet.cssRules.item(ruleIndex);
    },
    addProperty: function addProperty(device, selector, property, value) {
      var isImportant = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
      var sheet = this.sheets[device];

      if (sheet === undefined) {
        return;
      }

      var ruleIndex = this.getRuleIndex(device, selector);

      if (ruleIndex === null) {
        ruleIndex = this.addRule(sheet, device, selector);
      }

      var rule = this.getRule(sheet, ruleIndex);
      var style = rule.style;
      var important = '';

      if (isImportant) {
        important = 'important';
      }

      var oldValue = style[property];

      if (oldValue !== value) {
        style.setProperty(property, value, important);
      }
    },
    watch: function watch(control, cssItems) {
      var _this = this;

      if (control.params.responsive) {
        Object.keys(this.sheets).forEach(function (device) {
          if (control.settings[device] !== undefined) {
            var settingObj = control.settings[device];
            var initValue = settingObj.get();

            if (control.processCSSValue !== undefined) {
              control.processCSSValue(function (processedValue) {
                _this.processCSS(device, processedValue, cssItems);
              }, initValue, settingObj, device);
            } else {
              _this.processCSS(device, initValue, cssItems);
            }

            settingObj.bind(function (newValue) {
              if (control.processCSSValue !== undefined) {
                control.processCSSValue(function (processedValue) {
                  _this.processCSS(device, processedValue, cssItems);
                }, newValue, settingObj, device);
              } else {
                _this.processCSS(device, newValue, cssItems);
              }
            });
          }
        });
      } else if (control.settings.default !== undefined) {
        var settingObj = control.settings.default;
        var initValue = settingObj.get();

        if (control.processCSSValue !== undefined) {
          control.processCSSValue(function (processedValue) {
            _this.processCSS('desktop', processedValue, cssItems);
          }, initValue, settingObj, 'desktop');
        } else {
          this.processCSS('desktop', initValue, cssItems);
        }

        settingObj.bind(function (newValue) {
          if (control.processCSSValue !== undefined) {
            newValue = control.processCSSValue(function (processedValue) {
              _this.processCSS('desktop', processedValue, cssItems);
            }, newValue, settingObj, 'desktop');
          } else {
            _this.processCSS('desktop', newValue, cssItems);
          }
        });
      }
    },
    processCSS: function processCSS(device, value, cssItems) {
      var _this2 = this;

      if (cssItems.length) {
        cssItems.forEach(function (cssItem) {
          if (cssItem.conditions !== undefined) {
            cssItem.conditions.forEach(function (condition) {
              var check = _this2.checkWork[condition.type](value, condition.check);

              if (check) {
                if (cssItem.replace !== undefined && cssItem.property !== undefined) {
                  var newValue = _.isEmpty(condition.value.toString()) ? 'initial' : cssItem.replace.replace(/\{\{(?:\s+)?value(?:\s+)?\}\}/g, condition.value);

                  _this2.addProperty(device, cssItem.selector, cssItem.property, newValue);
                } else if (cssItem.property !== undefined) {
                  _this2.addProperty(device, cssItem.selector, cssItem.property, condition.value);
                }
              }
            });
          } else if (cssItem.replace !== undefined && cssItem.replace !== '' && cssItem.property !== undefined) {
            var newValue = _.isEmpty(value.toString()) ? 'initial' : cssItem.replace.replace(/\{\{(?:\s+)?value(?:\s+)?\}\}/g, value);

            _this2.addProperty(device, cssItem.selector, cssItem.property, newValue);
          } else if (cssItem.property !== undefined) {
            _this2.addProperty(device, cssItem.selector, cssItem.property, value);
          }
        });
      }
    },
    checkWork: checkWork
  });
  api.bind('ready', function () {
    api.previewer.bind('ready', function () {
      var iframe = api.previewer.preview.iframe.length ? api.previewer.preview.iframe[0] : {};
      customizely.css = new CSSViewer(iframe.contentWindow, iframe.contentDocument, customizely.css !== undefined);
      api.trigger('cmlycssready');
    });
  });
  customizely.Control = api.Control.extend({
    ready: function ready() {
      var _this3 = this;

      this.readyInput();
      this.description();
      this.checkDepends();

      if (this.params.responsive) {
        this.responsive();
      }

      api.bind('cmlycssready', function () {
        _this3.css();
      });
    },
    readyInput: function readyInput() {
      var control = this;
      control.container.on('change keyup paste', 'input', function () {
        control.setValue($(this).val());
      });
    },
    setValue: function setValue(value) {
      if (this.params.responsive) {
        var device = api.previewedDevice !== undefined ? api.previewedDevice.get() : 'desktop';
        this.settings[device].set(value);
      } else {
        this.setting.set(value);
      }
    },
    description: function description() {
      var control = this;
      var btn = control.container.find('.customizely-control-wrap .customizely-toggle-desc');
      var desc = control.container.find('.customizely-control-wrap .customize-control-description');
      btn.on('click', function (e) {
        e.preventDefault();
        btn.toggleClass('is-active');
        desc.toggleClass('is-active');
        desc.slideToggle('fast');
      });
    },
    responsive: function responsive() {
      var _this4 = this;

      var responsiveContainer = this.container.find('.customizely-control-responsive');
      responsiveContainer.find('button').on('click', function (e) {
        if (responsiveContainer.hasClass('customizely-expanded')) {
          if ($(e.currentTarget).hasClass('is-active')) {
            responsiveContainer.removeClass('customizely-expanded');
          } else {
            api.previewedDevice.set($(e.currentTarget).data('device'));
          }
        } else {
          responsiveContainer.addClass('customizely-expanded');
        }
      });
      api.bind('ready', function () {
        api.previewedDevice.bind(function (newDevice) {
          responsiveContainer.find('button.is-active').removeClass('is-active');
          responsiveContainer.find('button.customizely-device-' + newDevice).addClass('is-active');

          if (_this4.params.responsive) {
            _this4.setting = _this4.settings[newDevice];

            _this4.resetValue(_this4.setting.get());

            _this4.onChangeDevice(newDevice);
          }
        });
      });
    },
    resetValue: function resetValue(newValue) {
      this.container.find('input').val(newValue);
    },
    onChangeDevice: function onChangeDevice(newDevice) {
      return newDevice;
    },
    checkDepends: function checkDepends(noBind) {
      var control = this;
      var depends = control.params.depends;
      var showIt = control.showIt();
      var needBind = noBind ? false : true;

      if (depends) {
        showIt.set(control.dependsWork(depends, needBind));
      }
    },
    showIt: function showIt() {
      var control = this;
      return {
        value: true,
        set: function set(value) {
          this.value = value;

          if (value) {
            control.container.removeClass('deactivate');
          } else {
            control.container.addClass('deactivate');
          }
        },
        get: function get() {
          return this.value;
        }
      };
    },
    dependsWork: function dependsWork(depends, needBind) {
      var control = this,
          items = depends.items,
          relation = depends.relation || '&',
          values = [];

      _.each(items, function (item) {
        if (!item.items) {
          var settingObject = api.value(item.id);
          values.push(control.checkWork[item.check](settingObject.get(), item.value));

          if (needBind) {
            settingObject.bind(function () {
              control.checkDepends(true);
            });
          }
        }
      });

      if (values.length > 1) {
        return control.relationWork[relation](values);
      }

      return values[0];
    },
    checkWork: checkWork,
    relationWork: {
      '&': function _(a) {
        return a.every(function (b) {
          return b === true;
        });
      }
    },
    css: function css() {
      var cssItems = this.params.css;

      if (!cssItems.length || this.setting.transport === 'refresh') {
        return;
      }

      if (customizely.css !== undefined) {
        customizely.css.watch(this, cssItems);
      }
    },
    getNotificationsContainerElement: function getNotificationsContainerElement() {
      var control = this;
      var notificationsContainer;
      notificationsContainer = control.container.find('.customize-control-notifications-container:first');

      if (notificationsContainer.length) {
        return notificationsContainer;
      }

      notificationsContainer = $('<div class="customize-control-notifications-container"></div>');

      if (control.container.find('.customizely-control-wrap').hasClass('customizely-inline-control')) {
        control.container.find('.customizely-control-input').after(notificationsContainer);
      } else {
        control.container.find('.customizely-control-title').after(notificationsContainer);
      }

      return notificationsContainer;
    }
  });
  customizely.NumberControl = customizely.Control.extend({
    getIntValue: function getIntValue() {
      var theInput = this.container.find('.customizely-input-number > .customizely-number-input');
      var theValue = parseFloat(theInput.val());
      return theValue;
    },
    getUnit: function getUnit() {
      var theInput = this.container.find('.customizely-input-number > .customizely-number-input');
      var theValue = theInput.val();
      var theUnit = this.getUnitByValue(theValue);

      if (!this.params.no_unit && theUnit === '') {
        theUnit = this.params.unit;
      }

      return theUnit;
    },
    getUnitByValue: function getUnitByValue(unitValue) {
      if (unitValue === undefined && unitValue === '' && typeof unitValue !== 'string') {
        return '';
      }

      var numberValue = parseFloat(unitValue);
      var theUnit = unitValue.replace(numberValue, '');
      return theUnit;
    },
    work: function work() {
      var workType = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'plus';
      var number = this.getIntValue();
      var _this$params = this.params,
          min = _this$params.min,
          max = _this$params.max,
          step = _this$params.step;
      min = min === undefined ? 0 : min;
      max = max === undefined ? 100 : max;
      step = step === undefined ? 1 : step;
      min = parseFloat(min);
      max = parseFloat(max);
      step = parseFloat(step);
      var newNumber = number;

      if (isNaN(newNumber)) {
        newNumber = 0;
      }

      if (workType === 'plus') {
        if (newNumber >= max) {
          return;
        }

        newNumber = newNumber + step;
      } else {
        if (newNumber <= min) {
          return;
        }

        newNumber = newNumber - step;
      }

      newNumber = parseFloat(newNumber.toFixed(2));
      var unit = this.getUnit();
      newNumber += unit;
      this.setNumberValue(newNumber);
    },
    up: function up() {
      this.work();
    },
    down: function down() {
      this.work('minus');
    },
    setNumberValue: function setNumberValue(newValue) {
      var theInput = this.container.find('.customizely-input-number > .customizely-number-input');
      var isValidUnit = this.checkValidUnit(newValue);

      if (isValidUnit !== -1 && isValidUnit === false) {
        this.notifications.add(new api.Notification('invalid_unit', {
          message: __('Invalid Unit', 'customizely'),
          type: 'error'
        }));
      } else {
        this.notifications.remove('invalid_unit');
      }

      theInput.val(newValue);
      this.setValue(newValue);
    },
    readyInput: function readyInput() {
      var _this5 = this;

      this.container.on('change', '.customizely-number-input', function (e) {
        _this5.setNumberValue(e.target.value);
      });
      this.container.on('keydown', '.customizely-number-input', function (e) {
        if (_this5.timer) {
          clearInterval(_this5.timer);
          delete _this5.timer;
        }

        if (e.key === 'ArrowUp') {
          e.preventDefault();

          _this5.up();
        } else if (e.key === 'ArrowDown') {
          e.preventDefault();

          _this5.down();
        }
      });
      this.container.on('click', '.customizely-number-input-up', function () {
        _this5.up();
      });
      this.container.on('click', '.customizely-number-input-down', function () {
        _this5.down();
      });

      if (this.extendReadyInput !== undefined) {
        this.extendReadyInput();
      }
    },
    checkValidUnit: function checkValidUnit(newValue) {
      if (this.params.no_unit || !Array.isArray(this.params.units) || newValue === undefined || newValue === '') {
        return -1;
      }

      var theUnit = this.getUnitByValue(newValue);
      return this.params.units.indexOf(theUnit) !== -1;
    }
  });
})(wp, jQuery, window);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_buttons = cmly.Control.extend({
    resetValue: function resetValue(newValue) {
      this.container.find('input[type="radio"]').each(function () {
        if (newValue === $(this).val()) {
          $(this).prop('checked', true);
        } else {
          $(this).prop('checked', false);
        }
      });
    }
  });
})(wp, customizely, jQuery);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_checkbox = cmly.Control.extend({
    readyInput: function readyInput() {
      var control = this,
          mainContainer = this.container;
      mainContainer.on('change', 'input[type="checkbox"]', function () {
        var allVal = [];
        mainContainer.find('input[type="checkbox"]:checked').each(function () {
          allVal.push($(this).val());
        });
        control.setting.set(allVal);
      });
    },
    resetValue: function resetValue(newValue) {
      var isEmpty = Array.isArray(newValue) ? newValue.length === 0 : true;
      this.container.find('input[type="checkbox"]').each(function () {
        if (isEmpty) {
          $(this).prop('checked', false);
        } else if (newValue.indexOf($(this).val()) !== -1) {
          $(this).prop('checked', true);
        } else {
          $(this).prop('checked', false);
        }
      }); // this.container.find( 'textarea' ).val( newValue );
    }
  });
})(wp, customizely, jQuery);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_color_palette = cmly.Control.extend({
    resetValue: function resetValue(newValue) {
      this.container.find('input[type="radio"]').each(function () {
        if (newValue === $(this).val()) {
          $(this).prop('checked', true);
        } else {
          $(this).prop('checked', false);
        }
      });
    }
  });
})(wp, customizely, jQuery);
/*global customizely */
(function (api, cmly) {
  api.customize.controlConstructor.cmly_color = cmly.Control.extend({
    readyInput: function readyInput() {
      var control = this;
      var colorInput = control.container.find('input.customizely-color-input');
      colorInput.wpColorPicker({
        mode: 'hsl',
        defaultColor: this.params.default,
        width: 260,
        change: function change() {
          control.setValue(colorInput.wpColorPicker('color'));
        },
        clear: function clear() {
          control.setValue('');
        }
      });
      /* control.setting.bind( ( value ) => {
      	// Bail if the update came from the control itself.
      	if ( updating ) {
      		return;
      	}
      	colorInput.val( value );
      	colorInput.wpColorPicker( 'color', value );
      } ); */
      // Collapse color picker when hitting Esc instead of collapsing the current section.

      control.container.on('keydown', function (event) {
        if (27 !== event.which) {
          // Esc.
          return;
        }

        var pickerContainer = control.container.find('.wp-picker-container');

        if (pickerContainer.hasClass('wp-picker-active')) {
          colorInput.wpColorPicker('close');
          control.container.find('.wp-color-result').focus();
          event.stopPropagation(); // Prevent section from being collapsed.
        }
      });
    },
    resetValue: function resetValue(newValue) {
      var colorInput = this.container.find('input.customizely-color-input');
      colorInput.val(newValue);

      if (colorInput.wpColorPicker !== undefined) {
        colorInput.wpColorPicker('color', newValue);
        this.container.find('.button.wp-color-result').css('backgroundColor', newValue);
      }
    },
    onChangeDevice: function onChangeDevice(newDevice) {
      var colorInput = this.container.find('input.customizely-color-input');
      var defaultValue = this.params['default_' + newDevice];

      if (newDevice === 'desktop') {
        defaultValue = this.params.default;
      }

      colorInput.wpColorPicker('defaultColor', defaultValue);
    }
  });
})(wp, customizely);
/*global customizely, kitIconPicker, cmlyIcons */
(function (api, cmly) {
  api.customize.controlConstructor.cmly_icons = cmly.Control.extend({
    readyInput: function readyInput() {
      var control = this;
      var iconInputs = control.container.find('.customizely-icon-input');

      if (iconInputs.length && iconInputs[0] !== undefined) {
        this.iconPicker = kitIconPicker(iconInputs[0], {
          icons: cmlyIcons || [],
          position: ['bottom right', 'top right'],
          events: {
            change: function change(icon) {
              control.setValue(icon);
            }
          }
        });
      }
    },
    resetValue: function resetValue(newValue) {
      if (this.iconPicker !== undefined) {
        this.iconPicker.value(newValue);
      }
    }
  });
})(wp, customizely);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_image_select = cmly.Control.extend({
    resetValue: function resetValue(newValue) {
      this.container.find('input[type="radio"]').each(function () {
        if (newValue === $(this).val()) {
          $(this).prop('checked', true);
        } else {
          $(this).prop('checked', false);
        }
      });
    }
  });
})(wp, customizely, jQuery);
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

/*global customizely, _ */
(function (api, cmly) {
  var apiFetch = api.apiFetch;
  var __ = api.i18n.__;
  api.customize.controlConstructor.cmly_media = cmly.Control.extend({
    frame: null,
    getImageById: function getImageById(id) {
      var req = apiFetch({
        path: '/wp/v2/media/' + id,
        method: 'GET'
      });
      return req;
    },
    renderMediaContent: function renderMediaContent(type, id, url) {
      var holder = this.container.find('.customizely-media-holder');
      this.container.addClass('customizely-has-media');

      if (type === 'video') {
        var previewTemplate = wp.template('wp-media-widget-video-preview');
        this.container.addClass('customizely-media-audio-video');
        holder.html(previewTemplate({
          model: {
            attachment_id: id,
            html: '',
            src: url
          }
        }));
        wp.mediaelement.initialize();
      } else if (type === 'audio') {
        var _previewTemplate = wp.template('wp-media-widget-audio-preview');

        this.container.addClass('customizely-media-audio-video');
        holder.html(_previewTemplate({
          model: {
            attachment_id: id,
            html: '',
            src: url
          }
        }));
        wp.mediaelement.initialize();
      } else {
        this.container.removeClass('customizely-media-audio-video');
        holder.html('<img src="' + url + '" />');
      }
    },
    readyInput: function readyInput() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
        var control, mainContainer, holder, buttons, value, _control$params, modalTitle, mediaType, modalButtonTitle, attachment, url, config;

        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                control = _this;
                mainContainer = control.container;
                holder = mainContainer.find('.customizely-media-holder');
                buttons = mainContainer.find('.customizely-media-buttons');
                value = control.setting.get();
                _control$params = control.params, modalTitle = _control$params.modalTitle, mediaType = _control$params.mediaType, modalButtonTitle = _control$params.modalButtonTitle;

                if (!value) {
                  _context.next = 12;
                  break;
                }

                _context.next = 9;
                return control.getImageById(value);

              case 9:
                attachment = _context.sent;
                url = attachment.cmly_thumb !== undefined ? attachment.cmly_thumb : '';
                control.renderMediaContent(attachment.cmly_type, attachment.id, url);

              case 12:
                if (!control.frame) {
                  // Create a new media frame
                  config = {
                    title: _.isEmpty(modalTitle) ? __('Select or Upload Media Of Your Chosen Persuasion', 'customizely') : modalTitle,
                    button: {
                      text: _.isEmpty(modalButtonTitle) ? __('Select', 'customizely') : modalButtonTitle
                    },
                    multiple: false
                  };

                  if (mediaType !== undefined && mediaType !== 'all') {
                    config.library = {
                      type: mediaType
                    };
                  }

                  control.frame = wp.media(config);
                }

                control.frame.on('select', function () {
                  var attachment = control.frame.state().get('selection').first().toJSON();
                  var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : false;

                  if (!url && (attachment.type === 'image' || attachment.type === 'video' || attachment.type === 'audio')) {
                    url = attachment.url;
                  } else if (!url) {
                    url = attachment.icon;
                  }

                  control.renderMediaContent(attachment.type, attachment.id, url);
                  control.setting.set(attachment.id);
                  buttons.addClass('active');
                });
                mainContainer.on('click', '.customizely-media-holder, .customizely-media-buttons .customizely-add-media', function () {
                  var valueCurrent = control.setting.get();

                  if (!_.isEmpty(valueCurrent.toString())) {
                    var state = control.frame.state();

                    if (state !== undefined) {
                      var selection = state.get('selection');
                      selection.add(wp.media.attachment(valueCurrent));
                    }
                  }

                  control.frame.open();
                });
                mainContainer.on('click', '.customizely-media-buttons .customizely-remove-media', function () {
                  control.setting.set('');
                  holder.html('<div class="customizely-media-placeholder"><span class="dashicons dashicons-plus"></span></div>');
                  buttons.removeClass('active');
                  mainContainer.removeClass('customizely-media-audio-video');
                  mainContainer.removeClass('customizely-has-media');
                });

              case 16:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    processCSSValue: function processCSSValue(callback, value) {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee2() {
        var attachment;
        return regeneratorRuntime.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                if (!_.isEmpty(value.toString())) {
                  _context2.next = 3;
                  break;
                }

                callback('');
                return _context2.abrupt("return");

              case 3:
                _context2.next = 5;
                return _this2.getImageById(value);

              case 5:
                attachment = _context2.sent;
                callback(attachment.source_url);

              case 7:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    }
  });
})(wp, customizely);
/*global customizely */
(function (api, cmly) {
  api.customize.controlConstructor.cmly_number = cmly.NumberControl.extend({});
})(wp, customizely);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_radio = cmly.Control.extend({
    resetValue: function resetValue(newValue) {
      this.container.find('input[type="radio"]').each(function () {
        if (newValue === $(this).val()) {
          $(this).prop('checked', true);
        } else {
          $(this).prop('checked', false);
        }
      });
    }
  });
})(wp, customizely, jQuery);
/*global customizely */
(function (api, cmly) {
  var __ = api.i18n.__;
  api.customize.controlConstructor.cmly_range = cmly.NumberControl.extend({
    setNumberValue: function setNumberValue(newValue) {
      var theInput = this.container.find('.customizely-input-number > .customizely-number-input');
      var rangeInput = this.container.find('.customizely-range-input');
      var number = parseFloat(newValue);

      if (isNaN(number)) {
        number = 0;
      }

      var isValidUnit = this.checkValidUnit(newValue);

      if (isValidUnit !== -1 && isValidUnit === false) {
        this.notifications.add(new api.customize.Notification('invalid_unit', {
          message: __('Invalid Unit', 'customizely'),
          type: 'error'
        }));
      } else {
        this.notifications.remove('invalid_unit');
      }

      theInput.val(newValue);
      rangeInput.val(number);
      this.setValue(newValue);
    },
    extendReadyInput: function extendReadyInput() {
      var _this = this;

      this.container.on('change input', '.customizely-range-input', function (e) {
        var unit = _this.getUnit();

        var number = e.target.value;

        _this.setNumberValue(number + unit);
      });
    },
    resetValue: function resetValue(newValue) {
      var theInput = this.container.find('.customizely-input-number > .customizely-number-input');
      var rangeInput = this.container.find('.customizely-range-input');
      var number = parseFloat(newValue);

      if (isNaN(number)) {
        number = 0;
      }

      var isValidUnit = this.checkValidUnit(newValue);

      if (isValidUnit !== -1 && isValidUnit === false) {
        this.notifications.add(new api.customize.Notification('invalid_unit', {
          message: __('Invalid Unit', 'customizely'),
          type: 'error'
        }));
      } else {
        this.notifications.remove('invalid_unit');
      }

      theInput.val(newValue);
      rangeInput.val(number);
    }
  });
})(wp, customizely);
/*global customizely */
(function (api, cmly) {
  api.customize.controlConstructor.cmly_select = cmly.Control.extend({
    readyInput: function readyInput() {
      var control = this;
      var select = control.container.find('select');
      select.chosen({
        allow_single_deselect: true,
        disable_search_threshold: 15
      });
      select.on('change', function () {
        control.setValue(select.val());
      });
    },
    resetValue: function resetValue(newValue) {
      var select = this.container.find('select');
      select.val(newValue).trigger('chosen:updated');
    }
  });
})(wp, customizely);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_switch = cmly.Control.extend({
    readyInput: function readyInput() {
      var control = this;
      control.container.on('change', 'input', function () {
        if ($(this).is(':checked')) {
          control.setting.set(true);
        } else {
          control.setting.set(false);
        }
      });
    },
    resetValue: function resetValue(newValue) {
      this.container.find('input').prop('checked', newValue);
    }
  });
})(wp, customizely, jQuery);
/*global customizely */
(function (api, cmly) {
  api.customize.controlConstructor.cmly_text = cmly.Control.extend({});
})(wp, customizely);
/*global customizely, jQuery */
(function (api, cmly, $) {
  api.customize.controlConstructor.cmly_textarea = cmly.Control.extend({
    readyInput: function readyInput() {
      var control = this;
      control.container.on('change keyup paste', 'textarea', function () {
        control.setting.set($(this).val());
      });
    },
    resetValue: function resetValue(newValue) {
      this.container.find('textarea').val(newValue);
    }
  });
})(wp, customizely, jQuery);
/*global customizely */
(function (api, cmly) {
  api.customize.controlConstructor.cmly_url = cmly.Control.extend({});
})(wp, customizely);