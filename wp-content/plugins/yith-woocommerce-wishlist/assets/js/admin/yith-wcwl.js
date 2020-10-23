jQuery(document).ready((function(t){t.fn.dependency=function(e,n,i,c){var o=t(this);o.on("change",(function(){var _=n(o);t.each(e,(function(e,n){var i=t(n);if(i.length){var c=i.closest("tr");c.length&&(_?c.show().fadeTo("slow",1):c.is(":visible")?c.fadeTo("slow",0,(function(){c.hide()})):c.css("opacity",0).hide())}})),void 0!==i&&i(o,c)})).change()};var e=function(e,n){var i=this;i.settings={},i.modal=null,i._init=function(){i.settings=t.extend({template:e.data("template"),template_data:{},container:".yith-wcwl-wizard-modal",events:{}},n),"function"==typeof i.settings.events.init&&i.settings.events.init(e,n),i._initOpener()},i._initOpener=function(){e.on("click",(function(e){var n=t(this),c=i.settings.template_data;e.preventDefault(),"function"==typeof c&&(c=c(n)),n.WCBackboneModal({template:i.settings.template,variable:c});var o=t(i.settings.container);i._initEditor(o),i._initEnhancedSelect(o),i._initTabs(o),i._initSteps(o),i._initOptions(o,c),i._initEvents(o,i.settings.events)}))},i._initEditor=function(e){e.find(".with-editor").each((function(){var e=t(this),n=e.attr("id");tinymce.get(n)&&(restoreTextMode=tinymce.get(n).isHidden(),wp.editor.remove(n)),wp.editor.initialize(n,{tinymce:{wpautop:!0,init_instance_callback:function(t){t.on("Change",(function(n){e.val(t.getContent()).change()}))}},quicktags:!0,mediaButtons:!0})}))},i._initEnhancedSelect=function(e){t(document.body).trigger("wc-enhanced-select-init")},i._initTabs=function(e){e.find(".tabs").on("click","a",(function(e){var n=t(this),i=n.closest("ul"),c=i.find("a"),o=i.parent().find(".tab"),_=n.data("target"),a=t(_),s=!1;e.preventDefault(),n.hasClass("active")||(s=!0),c.attr("aria-selected","false").removeClass("active"),n.attr("aria-selected","true").addClass("active"),o.attr("aria-expanded","false").removeClass("active").hide(),a.attr("aria-expanded","true").addClass("active").show(),s&&n.trigger("tabChange")}))},i._initOptions=function(e,n){t.each(n,(function(t,n){var i=e.find('[name="'+t+'"]');i.length&&n!==i.val()&&(i.is("select")&&!i.find('option[value="'+n+'"]').length?i.append('<option value="'+n+'" selected="selected">'+n+" </option>"):i.val(n))}))},i._initSteps=function(e){e.find(".step").hide().first().show(),e.find(".continue-button").on("click",(function(n){var c=t(this).closest(".step"),o=c.next(".step");n.preventDefault(),o.length&&i._changeStep(e,c,o)})),e.find(".back-button").on("click",(function(n){var c=t(this).closest(".step"),o=c.prev(".step");n.preventDefault(),o.length&&i._changeStep(e,c,o)}))},i._initEvents=function(n,c){"function"==typeof i.settings.events.open&&i.settings.events.open(e,n),t.each(c,(function(e,i){"init"!==e&&"open"!==e&&("tabChange"===e?n.find(".tabs"):"stepChange"===e?n.find(".step"):n.find(":input")).on(e,(function(e){return i(t(this),n,e)}))}))},i._changeStep=function(t,e,n){e.animate({opacity:0},{duration:200,complete:function(){var i=t.find("article"),c=i.outerWidth(),o=i.outerHeight();i.outerWidth("auto"),i.outerHeight("auto"),e.hide(),n.show();var _=n.outerWidth(),a=n.outerHeight();n.hide(),e.css("opacity",1),i.outerWidth(c),i.outerHeight(o),i.animate({width:_,height:a},{duration:200,complete:function(){n.fadeIn(200)}})}}),n.trigger("stepChange")},i._init()},n=null,i=function(e,i,c){var o=i.find(".email-preview"),_=i.find("#template").val();n&&n.abort(),n=t.ajax({url:ajaxurl+"?action=preview_promotion_email",data:i.find("form").serialize(),method:"POST",beforeSend:function(){o.block({message:null,overlayCSS:{background:"transparent",opacity:.6}})},complete:function(){o.unblock()},success:function(t){o.removeClass("html plain").addClass(_).find(".no-interactions").html(t)}})},c=function(){return{template:"yith-wcwl-promotion-wizard",template_data:function(e){var n;return n=e.hasClass("restore-draft")?e.data("draft"):t.extend(n,{product_id:e.data("product_id"),user_id:e.data("user_id")})},events:{change:i,open:function(t,e,n){e.find("#content_html-tmce").click(),i(0,e)},tabChange:function(t,e,n){e.find("#template").val(t.find(".active").data("template")),i(0,e)},stepChange:function(e,n,i){var c=e.find(".receivers-count"),o=e.find(".show-on-long-queue"),_=o.data("threshold");c.length&&t.ajax({url:ajaxurl+"?action=calculate_promotion_email_receivers",data:n.find("form").serialize(),method:"post",beforeSend:function(){c.css("opacity",.3),o.length&&o.hide()},complete:function(){c.css("opacity",1)},success:function(t){void 0!==t.label&&(c.html(t.label),o.length&&void 0!==t.count&&t.count>_&&o.show())}})}}}};t.fn.wizard=function(n){var i=t(this);new e(i,n)},t(".create-promotion").wizard(c()),t(".restore-draft").wizard(c());var o=function(t){return t.is('input[type="radio"]')||(t=t.find('input[type="radio"]:checked')),"no"===t.val()},_=function(t){return t.is(":checked")},a=t("#yith_wcwl_disable_wishlist_for_unauthenticated_users"),s=t("#yith_wcwl_multi_wishlist_enable"),l=t("#yith_wcwl_enable_multi_wishlist_for_unauthenticated_users"),r=t("#yith_wcwl_modal_enable"),u=t("#yith_wcwl_loop_position"),h=t(".icon-select"),d=t('[name="yith_wcwl_add_to_cart_style"]'),w=t("#yith_wcwl_add_to_cart_icon"),m=t('[name="yith_wcwl_ask_an_estimate_style"]'),f=t("#yith_wcwl_ask_an_estimate_icon"),p=t("#yith_wcwl_enable_share"),y=t("#yith_wcwl_share_fb"),g=t("#yith_wcwl_fb_button_icon"),v=t("#yith_wcwl_share_twitter"),b=t("#yith_wcwl_tw_button_icon"),k=t("#yith_wcwl_share_pinterest"),x=t("#yith_wcwl_pr_button_icon"),C=t("#yith_wcwl_share_email"),S=t("#yith_wcwl_em_button_icon"),T=t("#yith_wcwl_share_whatsapp"),z=t("#yith_wcwl_wa_button_icon"),j=t("#yith_wcwl_show_estimate_button"),E=t("#yith_wcwl_show_additional_info_textarea"),H=t("#yith_wcwl_ask_an_estimate_fields"),O=t("#woocommerce_promotion_mail_settings\\[email_type\\]"),W=t("#woocommerce_yith_wcwl_back_in_stock_settings\\[enabled\\]"),D=t("#woocommerce_yith_wcwl_back_in_stock_settings\\[email_type\\]"),q=t("#woocommerce_yith_wcwl_on_sale_item_settings\\[enabled\\]"),B=t("#woocommerce_yith_wcwl_on_sale_item_settings\\[email_type\\]"),M=t('[id^="type_"]');u.add("select#yith_wcwl_button_position").on("change",(function(){var e=t(this);"shortcode"===e.val()?e.parent().next().find(".addon").show():e.parent().next().find(".addon").hide()})).change(),M.on("change",(function(){var e=t(this),n=e.val(),i=e.closest(".yith-toggle-content-row").next();"radio"===n||"select"===n?i.show().fadeTo("slow",1):i.is(":visible")?i.fadeTo("slow",0,(function(){i.hide()})):i.css("opacity",0).hide()})).change(),d.on("change",(function(){w.change()})),m.on("change",(function(){f.change()})),h.each((function(){t(this).select2({templateResult:function(e){return e.id?t('<span><i class="option-icon fa '+e.element.value.toLowerCase()+'" ></i> '+e.text+"</span>"):e.text}})})),a.dependency(["#yith_wcwl_enable_multi_wishlist_for_unauthenticated_users-yes"],(function(){return o(a)&&_(s)}),(function(){l.change()})),s.dependency(["#yith_wcwl_enable_multi_wishlist_for_unauthenticated_users-yes"],(function(){return o(a)&&_(s)}),(function(){l.change()})),l.dependency(["#yith_wcwl_show_login_notice","#yith_wcwl_login_anchor_text"],(function(){return _(s)&&o(a)&&o(l)})),r.dependency(["#yith_wcwl_show_exists_in_a_wishlist"],(function(){var e="default"!==r.find(":checked").val();return e||t("#yith_wcwl_show_exists_in_a_wishlist").prop("checked",!0),e})),w.dependency(["#yith_wcwl_add_to_cart_custom_icon"],(function(){return"custom"===w.val()&&"button_custom"===d.filter(":checked").val()})),f.dependency(["#yith_wcwl_ask_an_estimate_custom_icon"],(function(){return"custom"===f.val()&&"button_custom"===m.filter(":checked").val()})),p.dependency(["#yith_wcwl_share_fb"],_,(function(){y.change(),g.change(),v.change(),b.change(),k.change(),x.change(),C.change(),S.change(),T.change(),z.change()})),y.dependency(["#yith_wcwl_fb_button_icon","#yith_wcwl_color_fb_button_background"],(function(){return _(y)&&_(p)}),(function(){g.change()})),g.dependency(["#yith_wcwl_fb_button_custom_icon"],(function(){return _(y)&&_(p)&&"custom"===g.val()})),v.dependency(["#yith_wcwl_tw_button_icon","#yith_wcwl_color_tw_button_background"],(function(){return _(v)&&_(p)}),(function(){b.change()})),b.dependency(["#yith_wcwl_tw_button_custom_icon"],(function(){return _(v)&&_(p)&&"custom"===b.val()})),k.dependency(["#yith_wcwl_socials_image_url","#yith_wcwl_pr_button_icon","#yith_wcwl_color_pr_button_background"],(function(){return _(k)&&_(p)}),(function(){x.change()})),x.dependency(["#yith_wcwl_pr_button_custom_icon"],(function(){return _(k)&&_(p)&&"custom"===x.val()})),C.dependency(["#yith_wcwl_em_button_icon","#yith_wcwl_color_em_button_background"],(function(){return _(C)&&_(p)}),(function(){S.change()})),S.dependency(["#yith_wcwl_em_button_custom_icon"],(function(){return _(C)&&_(p)&&"custom"===S.val()})),T.dependency(["#yith_wcwl_wa_button_icon","#yith_wcwl_wa_button_custom_icon","#yith_wcwl_color_wa_button_background"],(function(){return _(T)&&_(p)}),(function(){z.change()})),z.dependency(["#yith_wcwl_wa_button_custom_icon"],(function(){return _(T)&&_(p)&&"custom"===z.val()})),v.add(k).dependency(["#yith_wcwl_socials_title","#yith_wcwl_socials_text"],(function(){return(_(v)||_(k))&&_(p)})),j.dependency(["#yith_wcwl_show_additional_info_textarea"],_,(function(){E.change()})),j.on("change",(function(){E.change()})),E.dependency(["#yith_wcwl_additional_info_textarea_label"],(function(){return _(j)&&_(E)})),E.on("change",(function(){t(this).is(":checked")&&j.is(":checked")?H.removeClass("yith-disabled"):H.addClass("yith-disabled")})),O.dependency(["#woocommerce_promotion_mail_settings\\[content_html\\]"],(function(){return"multipart"===O.val()||"html"===O.val()})),O.dependency(["#woocommerce_promotion_mail_settings\\[content_text\\]"],(function(){return"multipart"===O.val()||"plain"===O.val()})),W.dependency(["#woocommerce_yith_wcwl_back_in_stock_settings\\[product_exclusions\\]","#woocommerce_yith_wcwl_back_in_stock_settings\\[category_exclusions\\]","#woocommerce_yith_wcwl_back_in_stock_settings\\[email_type\\]","#woocommerce_yith_wcwl_back_in_stock_settings\\[heading\\]","#woocommerce_yith_wcwl_back_in_stock_settings\\[subject\\]","#woocommerce_yith_wcwl_back_in_stock_settings\\[content_html\\]","#woocommerce_yith_wcwl_back_in_stock_settings\\[content_text\\]"],(function(){return _(W)}),(function(){D.change()})),D.dependency(["#woocommerce_yith_wcwl_back_in_stock_settings\\[content_html\\]"],(function(){return("multipart"===D.val()||"html"===D.val())&&_(W)})),D.dependency(["#woocommerce_yith_wcwl_back_in_stock_settings\\[content_text\\]"],(function(){return("multipart"===D.val()||"plain"===D.val())&&_(W)})),q.dependency(["#woocommerce_yith_wcwl_on_sale_item_settings\\[product_exclusions\\]","#woocommerce_yith_wcwl_on_sale_item_settings\\[category_exclusions\\]","#woocommerce_yith_wcwl_on_sale_item_settings\\[email_type\\]","#woocommerce_yith_wcwl_on_sale_item_settings\\[heading\\]","#woocommerce_yith_wcwl_on_sale_item_settings\\[subject\\]","#woocommerce_yith_wcwl_on_sale_item_settings\\[content_html\\]","#woocommerce_yith_wcwl_on_sale_item_settings\\[content_text\\]"],(function(){return _(q)}),(function(){B.change()})),B.dependency(["#woocommerce_yith_wcwl_on_sale_item_settings\\[content_html\\]"],(function(){return("multipart"===B.val()||"html"===B.val())&&_(q)})),B.dependency(["#woocommerce_yith_wcwl_on_sale_item_settings\\[content_text\\]"],(function(){return("multipart"===B.val()||"plain"===B.val())&&_(q)})),t(document).on("yith-add-box-button-toggle",(function(){var e=t("#new_type"),n=t("#new_options").closest(".yith-add-box-row");e.on("change",(function(){var i=e.val();"radio"===i||"select"===i?n.show().fadeTo("slow",1):n.is(":visible")?n.fadeTo("slow",0,(function(){t(this).hide()})):n.css("opacity",0).hide()})).change()}))}));