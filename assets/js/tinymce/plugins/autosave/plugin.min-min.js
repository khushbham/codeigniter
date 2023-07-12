tinymce.PluginManager.add("autosave",function(e){function t(e,t){var n={s:1e3,m:6e4};return e=/^(\d+)([ms]?)$/.exec(""+(e||t)),(e[2]?n[e[2]]:1)*parseInt(e,10)}function n(){var e=parseInt(d.getItem(c+"time"),10)||0;return(new Date).getTime()-e>p.autosave_retention?(r(!1),!1):!0}function r(t){d.removeItem(c+"draft"),d.removeItem(c+"time"),t!==!1&&e.fire("RemoveDraft")}function i(){l()||(d.setItem(c+"draft",e.getContent({format:"raw",no_events:!0})),d.setItem(c+"time",(new Date).getTime()),e.fire("StoreDraft"))}function s(){n()&&(e.setContent(d.getItem(c+"draft"),{format:"raw"}),e.fire("RestoreDraft"))}function o(){h||(setInterval(function(){e.removed||i()},p.autosave_interval),h=!0)}function u(){var t=this;t.disabled(!n()),e.on("StoreDraft RestoreDraft RemoveDraft",function(){t.disabled(!n())}),o()}function a(){e.undoManager.beforeChange(),s(),r(),e.undoManager.add()}function f(){var e;return tinymce.each(tinymce.editors,function(t){t.plugins.autosave&&t.plugins.autosave.storeDraft(),!e&&t.isDirty()&&t.getParam("autosave_ask_before_unload",!0)&&(e=t.translate("You have unsaved changes are you sure you want to navigate away?"))}),e}function l(t){var n=e.settings.forced_root_block;return t=tinymce.trim("undefined"==typeof t?e.getBody().innerHTML:t),""===t||(new RegExp("^<"+n+"[^>]*>(( |&nbsp;|[ 	]|<br[^>]*>)+?|)</"+n+">|<br>$","i")).test(t)}var c,h,p=e.settings,d=tinymce.util.LocalStorage;c=p.autosave_prefix||"tinymce-autosave-{path}{query}-{id}-",c=c.replace(/\{path\}/g,document.location.pathname),c=c.replace(/\{query\}/g,document.location.search),c=c.replace(/\{id\}/g,e.id),p.autosave_interval=t(p.autosave_interval,"30s"),p.autosave_retention=t(p.autosave_retention,"20m"),e.addButton("restoredraft",{title:"Restore last draft",onclick:a,onPostRender:u}),e.addMenuItem("restoredraft",{text:"Restore last draft",onclick:a,onPostRender:u,context:"file"}),e.settings.autosave_restore_when_empty!==!1&&(e.on("init",function(){n()&&l()&&s()}),e.on("saveContent",function(){r()})),window.onbeforeunload=f,this.hasDraft=n,this.storeDraft=i,this.restoreDraft=s,this.removeDraft=r,this.isEmpty=l});