tinymce.PluginManager.add("template",function(e){function t(t){return function(){var n=e.settings.templates;"string"==typeof n?tinymce.util.XHR.send({url:n,success:function(e){t(tinymce.util.JSON.parse(e))}}):t(n)}}function n(t){function n(t){function n(t){if(-1==t.indexOf("<html>")){var n="";tinymce.each(e.contentCSS,function(t){n+='<link type="text/css" rel="stylesheet" href="'+e.documentBaseURI.toAbsolute(t)+'">'}),t="<!DOCTYPE html><html><head>"+n+"</head>"+"<body>"+t+"</body>"+"</html>"}t=s(t,"template_preview_replace_values");var i=r.find("iframe")[0].getEl().contentWindow.document;i.open(),i.write(t),i.close()}var o=t.control.value();o.url?tinymce.util.XHR.send({url:o.url,success:function(e){i=e,n(i)}}):(i=o.content,n(i)),r.find("#description")[0].text(t.control.value().description)}var r,i,u=[];return t&&0!==t.length?(tinymce.each(t,function(e){u.push({selected:!u.length,text:e.title,value:{url:e.url,content:e.content,description:e.description}})}),r=e.windowManager.open({title:"Insert template",layout:"flex",direction:"column",align:"stretch",padding:15,spacing:10,items:[{type:"form",flex:0,padding:0,items:[{type:"container",label:"Templates",items:{type:"listbox",label:"Templates",name:"template",values:u,onselect:n}}]},{type:"label",name:"description",label:"Description",text:" "},{type:"iframe",flex:1,border:1}],onsubmit:function(){o(!1,i)},width:e.getParam("template_popup_width",600),height:e.getParam("template_popup_height",500)}),r.find("listbox")[0].fire("select"),void 0):(e.windowManager.alert("No templates defined"),void 0)}function r(t,n){function r(e,t){if(e=""+e,e.length<t)for(var n=0;n<t-e.length;n++)e="0"+e;return e}var i="Sun Mon Tue Wed Thu Fri Sat Sun".split(" "),s="Sunday Monday Tuesday Wednesday Thursday Friday Saturday Sunday".split(" "),o="Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),u="January February March April May June July August September October November December".split(" ");return n=n||new Date,t=t.replace("%D","%m/%d/%Y"),t=t.replace("%r","%I:%M:%S %p"),t=t.replace("%Y",""+n.getFullYear()),t=t.replace("%y",""+n.getYear()),t=t.replace("%m",r(n.getMonth()+1,2)),t=t.replace("%d",r(n.getDate(),2)),t=t.replace("%H",""+r(n.getHours(),2)),t=t.replace("%M",""+r(n.getMinutes(),2)),t=t.replace("%S",""+r(n.getSeconds(),2)),t=t.replace("%I",""+((n.getHours()+11)%12+1)),t=t.replace("%p",""+(n.getHours()<12?"AM":"PM")),t=t.replace("%B",""+e.translate(u[n.getMonth()])),t=t.replace("%b",""+e.translate(o[n.getMonth()])),t=t.replace("%A",""+e.translate(s[n.getDay()])),t=t.replace("%a",""+e.translate(i[n.getDay()])),t=t.replace("%%","%")}function i(t){var n=e.dom,r=e.getParam("template_replace_values");u(n.select("*",t),function(e){u(r,function(t,i){n.hasClass(e,i)&&"function"==typeof r[i]&&r[i](e)})})}function s(t,n){return u(e.getParam(n),function(e,n){"function"!=typeof e&&(t=t.replace(new RegExp("\\{\\$"+n+"\\}","g"),e))}),t}function o(t,n){function o(e,t){return(new RegExp("\\b"+t+"\\b","g")).test(e.className)}var a,f,c=e.dom,h=e.selection.getContent();n=s(n,"template_replace_values"),a=c.create("div",null,n),f=c.select(".mceTmpl",a),f&&f.length>0&&(a=c.create("div",null),a.appendChild(f[0].cloneNode(!0))),u(c.select("*",a),function(t){o(t,e.getParam("template_cdate_classes","cdate").replace(/\s+/g,"|"))&&(t.innerHTML=r(e.getParam("template_cdate_format",e.getLang("template.cdate_format")))),o(t,e.getParam("template_mdate_classes","mdate").replace(/\s+/g,"|"))&&(t.innerHTML=r(e.getParam("template_mdate_format",e.getLang("template.mdate_format")))),o(t,e.getParam("template_selected_content_classes","selcontent").replace(/\s+/g,"|"))&&(t.innerHTML=h)}),i(a),e.execCommand("mceInsertContent",!1,a.innerHTML),e.addVisual()}var u=tinymce.each;e.addCommand("mceInsertTemplate",o),e.addButton("template",{title:"Insert template",onclick:t(n)}),e.addMenuItem("template",{text:"Insert template",onclick:t(n),context:"insert"}),e.on("PreProcess",function(t){var n=e.dom;u(n.select("div",t.node),function(t){n.hasClass(t,"mceTmpl")&&(u(n.select("*",t),function(t){n.hasClass(t,e.getParam("template_mdate_classes","mdate").replace(/\s+/g,"|"))&&(t.innerHTML=r(e.getParam("template_mdate_format",e.getLang("template.mdate_format"))))}),i(t))})})});