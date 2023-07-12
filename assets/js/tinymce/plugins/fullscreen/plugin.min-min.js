tinymce.PluginManager.add("fullscreen",function(e){function t(){var e,t,n=window,r=document,i=r.body;return i.offsetWidth&&(e=i.offsetWidth,t=i.offsetHeight),n.innerWidth&&n.innerHeight&&(e=n.innerWidth,t=n.innerHeight),{w:e,h:t}}function n(){function n(){f.setStyle(v,"height",t().h-(p.clientHeight-v.clientHeight))}var h,p,v,m,g=document.body,y=document.documentElement;a=!a,p=e.getContainer(),h=p.style,v=e.getContentAreaContainer().firstChild,m=v.style,a?(r=m.width,i=m.height,m.width=m.height="100%",o=h.width,u=h.height,h.width=h.height="",f.addClass(g,"mce-fullscreen"),f.addClass(y,"mce-fullscreen"),f.addClass(p,"mce-fullscreen"),f.bind(window,"resize",n),n(),s=n):(m.width=r,m.height=i,o&&(h.width=o),u&&(h.height=u),f.removeClass(g,"mce-fullscreen"),f.removeClass(y,"mce-fullscreen"),f.removeClass(p,"mce-fullscreen"),f.unbind(window,"resize",s)),e.fire("FullscreenStateChanged",{state:a})}var r,i,s,o,u,a=!1,f=tinymce.DOM;return e.settings.inline?void 0:(e.on("init",function(){e.addShortcut("Ctrl+Alt+F","",n)}),e.on("remove",function(){s&&f.unbind(window,"resize",s)}),e.addCommand("mceFullScreen",n),e.addMenuItem("fullscreen",{text:"Fullscreen",shortcut:"Ctrl+Alt+F",selectable:!0,onClick:n,onPostRender:function(){var t=this;e.on("FullscreenStateChanged",function(e){t.active(e.state)})},context:"view"}),e.addButton("fullscreen",{tooltip:"Fullscreen",shortcut:"Ctrl+Alt+F",onClick:n,onPostRender:function(){var t=this;e.on("FullscreenStateChanged",function(e){t.active(e.state)})}}),{isFullscreen:function(){return a}})});