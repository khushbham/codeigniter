tinymce.PluginManager.add("importcss",function(e){function t(e){return"string"==typeof e?function(t){return-1!==t.indexOf(e)}:e instanceof RegExp?function(t){return e.test(t)}:e}function n(t,n){function r(e,t){var u,a=e.href;if((t||o[a])&&(!n||n(a))){s(e.imports,function(e){r(e,!0)});try{u=e.cssRules||e.rules}catch(f){}s(u,function(e){e.styleSheet?r(e.styleSheet,!0):e.selectorText&&s(e.selectorText.split(","),function(e){i.push(tinymce.trim(e))})})}}var i=[],o={};s(e.contentCSS,function(e){o[e]=!0});try{s(t.styleSheets,function(e){r(e)})}catch(u){}return i}function r(t){var n,r=/^(?:([a-z0-9\-_]+))?(\.[a-z0-9_\-\.]+)$/i.exec(t);if(r){var i=r[1],s=r[2].substr(1).split(".").join(" ");return r[1]?(n={title:t},e.schema.getTextBlockElements()[i]?n.block=i:e.schema.getBlockElements()[i]?n.selector=i:n.inline=i):r[2]&&(n={inline:"span",title:t.substr(1),classes:s}),e.settings.importcss_merge_classes!==!1?n.classes=s:n.attributes={"class":s},n}}var i=this,s=tinymce.each;e.on("renderFormatsMenu",function(o){var u=e.settings,a={},f=u.importcss_selector_converter||r,l=t(u.importcss_selector_filter);e.settings.importcss_append||e.settings.style_formats||o.control.items().remove();var c=[];tinymce.each(u.importcss_groups,function(e){e=tinymce.extend({},e),e.filter=t(e.filter),c.push(e)}),s(n(e.getDoc(),t(u.importcss_file_filter)),function(t){if(-1===t.indexOf(".mce-")&&!a[t]&&(!l||l(t))){var n,r=f.call(i,t);if(r){var s=r.name||tinymce.DOM.uniqueId();if(c)for(var u=0;u<c.length;u++)if(!c[u].filter||c[u].filter(t)){c[u].item||(c[u].item={text:c[u].title,menu:[]}),n=c[u].item.menu;break}e.formatter.register(s,r);var h=tinymce.extend({},o.control.settings.itemDefaults,{text:r.title,format:s});n?n.push(h):o.control.add(h)}a[t]=!0}}),s(c,function(e){o.control.add(e.item)}),o.control.renderNew()}),i.convertSelectorToFormat=r});