tinymce.PluginManager.add("advlist",function(e){function t(e,t){var n=[];return tinymce.each(t.split(/[ ,]/),function(e){n.push({text:e.replace(/\-/g," ").replace(/\b\w/g,function(e){return e.toUpperCase()}),data:"default"==e?"":e})}),n}function n(t,n){var r,i=e.dom,s=e.selection;r=i.getParent(s.getNode(),"ol,ul"),r&&r.nodeName==t&&n!==!1||e.execCommand("UL"==t?"InsertUnorderedList":"InsertOrderedList"),n=n===!1?o[t]:n,o[t]=n,r=i.getParent(s.getNode(),"ol,ul"),r&&(i.setStyle(r,"listStyleType",n),r.removeAttribute("data-mce-style")),e.focus()}function r(t){var n=e.dom.getStyle(e.dom.getParent(e.selection.getNode(),"ol,ul"),"listStyleType")||"";t.control.items().each(function(e){e.active(e.settings.data===n)})}var i,s,o={};i=t("OL",e.getParam("advlist_number_styles","default,lower-alpha,lower-greek,lower-roman,upper-alpha,upper-roman")),s=t("UL",e.getParam("advlist_bullet_styles","default,circle,disc,square")),e.addButton("numlist",{type:"splitbutton",tooltip:"Numbered list",menu:i,onshow:r,onselect:function(e){n("OL",e.control.settings.data)},onclick:function(){n("OL",!1)}}),e.addButton("bullist",{type:"splitbutton",tooltip:"Bullet list",menu:s,onshow:r,onselect:function(e){n("UL",e.control.settings.data)},onclick:function(){n("UL",!1)}})});