tinymce.PluginManager.add("image",function(e){function t(e,t){function n(e,n){r.parentNode.removeChild(r),t({width:e,height:n})}var r=document.createElement("img");r.onload=function(){n(r.clientWidth,r.clientHeight)},r.onerror=function(){n()},r.src=e;var i=r.style;i.visibility="hidden",i.position="fixed",i.bottom=i.left=0,i.width=i.height="auto",document.body.appendChild(r)}function n(t){return function(){var n=e.settings.image_list;"string"==typeof n?tinymce.util.XHR.send({url:n,success:function(e){t(tinymce.util.JSON.parse(e))}}):t(n)}}function r(n){function r(){var t=[{text:"None",value:""}];return tinymce.each(n,function(n){t.push({text:n.text||n.title,value:e.convertURL(n.value||n.url,"src"),menu:n.menu})}),t}function i(e){var t,n,r,i;t=f.find("#width")[0],n=f.find("#height")[0],r=t.value(),i=n.value(),f.find("#constrain")[0].checked()&&l&&c&&r&&i&&(e.control==t?(i=Math.round(r/l*i),n.value(i)):(r=Math.round(i/c*r),t.value(r))),l=r,c=i}function s(){function t(t){function r(){t.onload=t.onerror=null,e.selection.select(t),e.nodeChanged()}t.onload=function(){n.width||n.height||d.setAttribs(t,{width:t.clientWidth,height:t.clientHeight}),r()},t.onerror=r}var n=f.toJSON();""===n.width&&(n.width=null),""===n.height&&(n.height=null),""===n.style&&(n.style=null),n={src:n.src,alt:n.alt,width:n.width,height:n.height,style:n.style},e.undoManager.transact(function(){return n.src?(v?d.setAttribs(v,n):(n.id="__mcenew",e.selection.setContent(d.createHTML("img",n)),v=d.get("__mcenew"),d.setAttrib(v,"id",null)),t(v),void 0):(v&&(d.remove(v),e.nodeChanged()),void 0)})}function o(e){return e&&(e=e.replace(/px$/,"")),e}function u(){h&&h.value(e.convertURL(this.value(),"src")),t(this.value(),function(e){e.width&&e.height&&(l=e.width,c=e.height,f.find("#width").value(l),f.find("#height").value(c))})}function a(){function e(e){return e.length>0&&/^[0-9]+$/.test(e)&&(e+="px"),e}var t=f.toJSON(),n=d.parseStyle(t.style);delete n.margin,n["margin-top"]=n["margin-bottom"]=e(t.vspace),n["margin-left"]=n["margin-right"]=e(t.hspace),n["border-width"]=e(t.border),f.find("#style").value(d.serializeStyle(d.parseStyle(d.serializeStyle(n))))}var f,l,c,h,p={},d=e.dom,v=e.selection.getNode();l=d.getAttrib(v,"width"),c=d.getAttrib(v,"height"),"IMG"!=v.nodeName||v.getAttribute("data-mce-object")||v.getAttribute("data-mce-placeholder")?v=null:p={src:d.getAttrib(v,"src"),alt:d.getAttrib(v,"alt"),width:l,height:c},n&&(h={type:"listbox",label:"Image list",values:r(),value:p.src&&e.convertURL(p.src,"src"),onselect:function(e){var t=f.find("#alt");(!t.value()||e.lastControl&&t.value()==e.lastControl.text())&&t.value(e.control.text()),f.find("#src").value(e.control.value())},onPostRender:function(){h=this}});var m=[{name:"src",type:"filepicker",filetype:"image",label:"Source",autofocus:!0,onchange:u},h,{name:"alt",type:"textbox",label:"Image description"},{type:"container",label:"Dimensions",layout:"flex",direction:"row",align:"center",spacing:5,items:[{name:"width",type:"textbox",maxLength:3,size:3,onchange:i},{type:"label",text:"x"},{name:"height",type:"textbox",maxLength:3,size:3,onchange:i},{name:"constrain",type:"checkbox",checked:!0,text:"Constrain proportions"}]}];e.settings.image_advtab?(v&&(p.hspace=o(v.style.marginLeft||v.style.marginRight),p.vspace=o(v.style.marginTop||v.style.marginBottom),p.border=o(v.style.borderWidth),p.style=e.dom.serializeStyle(e.dom.parseStyle(e.dom.getAttrib(v,"style")))),f=e.windowManager.open({title:"Insert/edit image",data:p,bodyType:"tabpanel",body:[{title:"General",type:"form",items:m},{title:"Advanced",type:"form",pack:"start",items:[{label:"Style",name:"style",type:"textbox"},{type:"form",layout:"grid",packV:"start",columns:2,padding:0,alignH:["left","right"],defaults:{type:"textbox",maxWidth:50,onchange:a},items:[{label:"Vertical space",name:"vspace"},{label:"Horizontal space",name:"hspace"},{label:"Border",name:"border"}]}]}],onSubmit:s})):f=e.windowManager.open({title:"Insert/edit image",data:p,body:m,onSubmit:s})}e.addButton("image",{icon:"image",tooltip:"Insert/edit image",onclick:n(r),stateSelector:"img:not([data-mce-object],[data-mce-placeholder])"}),e.addMenuItem("image",{icon:"image",text:"Insert image",onclick:n(r),context:"insert",prependToContext:!0})});