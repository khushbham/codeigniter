!function(e,t){"use strict";function n(e,t){for(var n,r=[],i=0;i<e.length;++i){if(n=u[e[i]]||s(e[i]),!n)throw"module definition dependecy not found: "+e[i];r.push(n)}t.apply(null,r)}function r(e,r,i){if("string"!=typeof e)throw"invalid module definition, module id must be defined and be a string";if(r===t)throw"invalid module definition, dependencies must be specified";if(i===t)throw"invalid module definition, definition function must be specified";n(r,function(){u[e]=i.apply(null,arguments)})}function i(e){return!!u[e]}function s(t){for(var n=e,r=t.split(/[.\/]/),i=0;i<r.length;++i){if(!n[r[i]])return;n=n[r[i]]}return n}function o(n){for(var r=0;r<n.length;r++){for(var i=e,s=n[r],o=s.split(/[.\/]/),a=0;a<o.length-1;++a)i[o[a]]===t&&(i[o[a]]={}),i=i[o[a]];i[o[o.length-1]]=u[s]}}var u={},a="tinymce/tableplugin/TableGrid",f="tinymce/util/Tools",l="tinymce/Env",c="tinymce/tableplugin/Quirks",h="tinymce/util/VK",p="tinymce/tableplugin/CellSelection",d="tinymce/dom/TreeWalker",v="tinymce/tableplugin/Plugin",m="tinymce/PluginManager";r(a,[f,l],function(e,t){function n(e,t){return parseInt(e.getAttribute(t)||1,10)}var r=e.each;return function(i,s){function o(){var e=0;A=[],r(["thead","tbody","tfoot"],function(t){var i=P.select("> "+t+" tr",s);r(i,function(i,s){s+=e,r(P.select("> td, > th",i),function(e,r){var i,o,u,a;if(A[s])for(;A[s][r];)r++;for(u=n(e,"rowspan"),a=n(e,"colspan"),o=s;s+u>o;o++)for(A[o]||(A[o]=[]),i=r;r+a>i;i++)A[o][i]={part:t,real:o==s&&i==r,elm:e,rowspan:u,colspan:a}})}),e+=i.length})}function u(e,t){return e=e.cloneNode(t),e.removeAttribute("id"),e}function a(e,t){var n;return n=A[t],n?n[e]:void 0}function f(e,t,n){e&&(n=parseInt(n,10),1===n?e.removeAttribute(t,1):e.setAttribute(t,n,1))}function l(e){return e&&(P.hasClass(e.elm,"mce-item-selected")||e==_)}function c(){var e=[];return r(s.rows,function(t){r(t.cells,function(n){return P.hasClass(n,"mce-item-selected")||n==_.elm?(e.push(t),!1):void 0})}),e}function h(){var e=P.createRng();e.setStartAfter(s),e.setEndAfter(s),D.setRng(e),P.remove(s)}function p(n){var s,o={};return i.settings.table_clone_elements!==!1&&(o=e.makeMap((i.settings.table_clone_elements||"strong em b i span font h1 h2 h3 h4 h5 h6 p div").toUpperCase(),/[ ,]/)),e.walk(n,function(e){var i;return 3==e.nodeType?(r(P.getParents(e.parentNode,null,n).reverse(),function(e){o[e.nodeName]&&(e=u(e,!1),s?i&&i.appendChild(e):s=i=e,i=e)}),i&&(i.innerHTML=t.ie?"&nbsp;":'<br data-mce-bogus="1" />'),!1):void 0},"childNodes"),n=u(n,!1),f(n,"rowSpan",1),f(n,"colSpan",1),s?n.appendChild(s):t.ie||(n.innerHTML='<br data-mce-bogus="1" />'),n}function d(){var e=P.createRng(),t;return r(P.select("tr",s),function(e){0===e.cells.length&&P.remove(e)}),0===P.select("tr",s).length?(e.setStartBefore(s),e.setEndBefore(s),D.setRng(e),P.remove(s),void 0):(r(P.select("thead,tbody,tfoot",s),function(e){0===e.rows.length&&P.remove(e)}),o(),t=A[Math.min(A.length-1,O.y)],t&&(D.select(t[Math.min(t.length-1,O.x)].elm,!0),D.collapse(!0)),void 0)}function v(e,t,n,r){var i,s,o,u,a;for(i=A[t][e].elm.parentNode,o=1;n>=o;o++)if(i=P.getNext(i,"tr")){for(s=e;s>=0;s--)if(a=A[t+o][s].elm,a.parentNode==i){for(u=1;r>=u;u++)P.insertAfter(p(a),a);break}if(-1==s)for(u=1;r>=u;u++)i.insertBefore(p(i.cells[0]),i.cells[0])}}function m(){r(A,function(e,t){r(e,function(e,r){var i,s,o;if(l(e)&&(e=e.elm,i=n(e,"colspan"),s=n(e,"rowspan"),i>1||s>1)){for(f(e,"rowSpan",1),f(e,"colSpan",1),o=0;i-1>o;o++)P.insertAfter(p(e),e);v(r,t,s-1,i)}})})}function g(t,n,i){var s,u,c,h,p,v,g,y,b,w,E;if(t?(s=N(t),u=s.x,c=s.y,h=u+(n-1),p=c+(i-1)):(O=M=null,r(A,function(e,t){r(e,function(e,n){l(e)&&(O||(O={x:n,y:t}),M={x:n,y:t})})}),u=O.x,c=O.y,h=M.x,p=M.y),y=a(u,c),b=a(h,p),y&&b&&y.part==b.part){for(m(),o(),y=a(u,c).elm,f(y,"colSpan",h-u+1),f(y,"rowSpan",p-c+1),g=c;p>=g;g++)for(v=u;h>=v;v++)A[g]&&A[g][v]&&(t=A[g][v].elm,t!=y&&(w=e.grep(t.childNodes),r(w,function(e){y.appendChild(e)}),w.length&&(w=e.grep(y.childNodes),E=0,r(w,function(e){"BR"==e.nodeName&&P.getAttrib(e,"data-mce-bogus")&&E++<w.length-1&&y.removeChild(e)})),P.remove(t)));d()}}function y(e){var t,i,s,o,a,c,h,d,v;for(r(A,function(n,i){return r(n,function(n){return l(n)&&(n=n.elm,a=n.parentNode,c=u(a,!1),t=i,e)?!1:void 0}),e?!t:void 0}),o=0;o<A[0].length;o++)if(A[t][o]&&(i=A[t][o].elm,i!=s)){if(e){if(t>0&&A[t-1][o]&&(d=A[t-1][o].elm,v=n(d,"rowSpan"),v>1)){f(d,"rowSpan",v+1);continue}}else if(v=n(i,"rowspan"),v>1){f(i,"rowSpan",v+1);continue}h=p(i),f(h,"colSpan",i.colSpan),c.appendChild(h),s=i}c.hasChildNodes()&&(e?a.parentNode.insertBefore(c,a):P.insertAfter(c,a))}function b(e){var t,i;r(A,function(n){return r(n,function(n,r){return l(n)&&(t=r,e)?!1:void 0}),e?!t:void 0}),r(A,function(r,s){var o,u,a;r[t]&&(o=r[t].elm,o!=i&&(a=n(o,"colspan"),u=n(o,"rowspan"),1==a?e?(o.parentNode.insertBefore(p(o),o),v(t,s,u-1,a)):(P.insertAfter(p(o),o),v(t,s,u-1,a)):f(o,"colSpan",o.colSpan+1),i=o))})}function w(){var t=[];r(A,function(i){r(i,function(i,s){l(i)&&-1===e.inArray(t,s)&&(r(A,function(e){var t=e[s].elm,r;r=n(t,"colSpan"),r>1?f(t,"colSpan",r-1):P.remove(t)}),t.push(s))})}),d()}function E(){function e(e){var t,i,s;t=P.getNext(e,"tr"),r(e.cells,function(e){var t=n(e,"rowSpan");t>1&&(f(e,"rowSpan",t-1),i=N(e),v(i.x,i.y,1,1))}),i=N(e.cells[0]),r(A[i.y],function(e){var t;e=e.elm,e!=s&&(t=n(e,"rowSpan"),1>=t?P.remove(e):f(e,"rowSpan",t-1),s=e)})}var t;t=c(),r(t.reverse(),function(t){e(t)}),d()}function S(){var e=c();return P.remove(e),d(),e}function x(){var e=c();return r(e,function(t,n){e[n]=u(t,!0)}),e}function T(e,t){var n=c(),i=n[t?0:n.length-1],s=i.cells.length;e&&(r(A,function(e){var t;return s=0,r(e,function(e){e.real&&(s+=e.colspan),e.elm.parentNode==i&&(t=1)}),t?!1:void 0}),t||e.reverse(),r(e,function(e){var n,r=e.cells.length,o;for(n=0;r>n;n++)o=e.cells[n],f(o,"colSpan",1),f(o,"rowSpan",1);for(n=r;s>n;n++)e.appendChild(p(e.cells[r-1]));for(n=s;r>n;n++)P.remove(e.cells[n]);t?i.parentNode.insertBefore(e,i):P.insertAfter(e,i)}),P.removeClass(P.select("td.mce-item-selected,th.mce-item-selected"),"mce-item-selected"))}function N(e){var t;return r(A,function(n,i){return r(n,function(n,r){return n.elm==e?(t={x:r,y:i},!1):void 0}),!t}),t}function C(e){O=N(e)}function k(){var e,t;return e=t=0,r(A,function(n,i){r(n,function(n,r){var s,o;l(n)&&(n=A[i][r],r>e&&(e=r),i>t&&(t=i),n.real&&(s=n.colspan-1,o=n.rowspan-1,s&&r+s>e&&(e=r+s),o&&i+o>t&&(t=i+o)))})}),{x:e,y:t}}function L(e){var t,n,r,i,s,o,u,a,f,l;if(M=N(e),O&&M){for(t=Math.min(O.x,M.x),n=Math.min(O.y,M.y),r=Math.max(O.x,M.x),i=Math.max(O.y,M.y),s=r,o=i,l=n;o>=l;l++)e=A[l][t],e.real||t-(e.colspan-1)<t&&(t-=e.colspan-1);for(f=t;s>=f;f++)e=A[n][f],e.real||n-(e.rowspan-1)<n&&(n-=e.rowspan-1);for(l=n;i>=l;l++)for(f=t;r>=f;f++)e=A[l][f],e.real&&(u=e.colspan-1,a=e.rowspan-1,u&&f+u>s&&(s=f+u),a&&l+a>o&&(o=l+a));for(P.removeClass(P.select("td.mce-item-selected,th.mce-item-selected"),"mce-item-selected"),l=n;o>=l;l++)for(f=t;s>=f;f++)A[l][f]&&P.addClass(A[l][f].elm,"mce-item-selected")}}var A,O,M,_,D=i.selection,P=D.dom;s=s||P.getParent(D.getStart(),"table"),o(),_=P.getParent(D.getStart(),"th,td"),_&&(O=N(_),M=k(),_=a(O.x,O.y)),e.extend(this,{deleteTable:h,split:m,merge:g,insertRow:y,insertCol:b,deleteCols:w,deleteRows:E,cutRows:S,copyRows:x,pasteRows:T,getPos:N,setStartCell:C,setEndCell:L})}}),r(c,[h,l,f],function(e,t,n){function r(e,t){return parseInt(e.getAttribute(t)||1,10)}var i=n.each;return function(n){function s(){function t(t){function s(e,r){var i=e?"previousSibling":"nextSibling",s=n.dom.getParent(r,"tr"),u=s[i];if(u)return m(n,r,u,e),t.preventDefault(),!0;var l=n.dom.getParent(s,"table"),c=s.parentNode,h=c.nodeName.toLowerCase();if("tbody"===h||h===(e?"tfoot":"thead")){var p=o(e,l,c,"tbody");if(null!==p)return a(e,p,r)}return f(e,s,i,l)}function o(e,t,r,i){var s=n.dom.select(">"+i,t),o=s.indexOf(r);if(e&&0===o||!e&&o===s.length-1)return u(e,t);if(-1===o){var a="thead"===r.tagName.toLowerCase()?0:s.length-1;return s[a]}return s[o+(e?-1:1)]}function u(e,t){var r=e?"thead":"tfoot",i=n.dom.select(">"+r,t);return 0!==i.length?i[0]:null}function a(e,r,i){var s=l(r,e);return s&&m(n,i,s,e),t.preventDefault(),!0}function f(e,r,i,o){var u=o[i];if(u)return c(u),!0;var a=n.dom.getParent(o,"td,th");if(a)return s(e,a,t);var f=l(r,!e);return c(f),t.preventDefault(),!1}function l(e,t){var r=e&&e[t?"lastChild":"firstChild"];return r&&"BR"===r.nodeName?n.dom.getParent(r,"td,th"):r}function c(e){n.selection.setCursorLocation(e,0)}function h(){return b==e.UP||b==e.DOWN}function p(e){var t=e.selection.getNode(),n=e.dom.getParent(t,"tr");return null!==n}function d(e){for(var t=0,n=e;n.previousSibling;)n=n.previousSibling,t+=r(n,"colspan");return t}function v(e,t){var n=0,s=0;return i(e.children,function(e,i){return n+=r(e,"colspan"),s=i,n>t?!1:void 0}),s}function m(e,t,r,i){var s=d(n.dom.getParent(t,"td,th")),o=v(r,s),u=r.childNodes[o],a=l(u,i);c(a||u)}function g(e){var t=n.selection.getNode(),r=n.dom.getParent(t,"td,th"),i=n.dom.getParent(e,"td,th");return r&&r!==i&&y(r,i)}function y(e,t){return n.dom.getParent(e,"TABLE")===n.dom.getParent(t,"TABLE")}var b=t.keyCode;if(h()&&p(n)){var w=n.selection.getNode();setTimeout(function(){g(w)&&s(!t.shiftKey&&b===e.UP,w,t)},0)}}n.on("KeyDown",function(e){t(e)})}function o(){function e(e,t){var n=t.ownerDocument,r=n.createRange(),i;return r.setStartBefore(t),r.setEnd(e.endContainer,e.endOffset),i=n.createElement("body"),i.appendChild(r.cloneContents()),0===i.innerHTML.replace(/<(br|img|object|embed|input|textarea)[^>]*>/gi,"-").replace(/<[^>]+>/g,"").length}n.on("KeyDown",function(t){var r,i,s=n.dom;(37==t.keyCode||38==t.keyCode)&&(r=n.selection.getRng(),i=s.getParent(r.startContainer,"table"),i&&n.getBody().firstChild==i&&e(r,i)&&(r=s.createRng(),r.setStartBefore(i),r.setEndBefore(i),n.selection.setRng(r),t.preventDefault()))})}function u(){n.on("KeyDown SetContent VisualAid",function(){var e;for(e=n.getBody().lastChild;e;e=e.previousSibling)if(3==e.nodeType){if(e.nodeValue.length>0)break}else if(1==e.nodeType&&!e.getAttribute("data-mce-bogus"))break;e&&"TABLE"==e.nodeName&&(n.settings.forced_root_block?n.dom.add(n.getBody(),n.settings.forced_root_block,n.settings.forced_root_block_attrs,t.ie&&t.ie<11?"&nbsp;":'<br data-mce-bogus="1" />'):n.dom.add(n.getBody(),"br",{"data-mce-bogus":"1"}))}),n.on("PreProcess",function(e){var t=e.node.lastChild;t&&("BR"==t.nodeName||1==t.childNodes.length&&("BR"==t.firstChild.nodeName||" "==t.firstChild.nodeValue))&&t.previousSibling&&"TABLE"==t.previousSibling.nodeName&&n.dom.remove(t)})}function a(){function e(e,t,n,r){var i=3,s=e.dom.getParent(t.startContainer,"TABLE"),o,u,a;return s&&(o=s.parentNode),u=t.startContainer.nodeType==i&&0===t.startOffset&&0===t.endOffset&&r&&("TR"==n.nodeName||n==o),a=("TD"==n.nodeName||"TH"==n.nodeName)&&!r,u||a}function t(){var t=n.selection.getRng(),r=n.selection.getNode(),i=n.dom.getParent(t.startContainer,"TD,TH");if(e(n,t,r,i)){i||(i=r);for(var s=i.lastChild;s.lastChild;)s=s.lastChild;t.setEnd(s,s.nodeValue.length),n.selection.setRng(t)}}n.on("KeyDown",function(){t()}),n.on("MouseDown",function(e){2!=e.button&&t()})}function f(){n.on("keydown",function(t){if((t.keyCode==e.DELETE||t.keyCode==e.BACKSPACE)&&!t.isDefaultPrevented()){var r=n.dom.getParent(n.selection.getStart(),"table");if(r){for(var i=n.dom.select("td,th",r),s=i.length;s--;)if(!n.dom.hasClass(i[s],"mce-item-selected"))return;t.preventDefault(),n.execCommand("mceTableDelete")}}})}f(),t.webkit&&(s(),a()),t.gecko&&(o(),u()),t.ie>10&&(o(),u())}}),r(p,[a,d,f],function(e,t,n){return function(r){function i(){r.getBody().style.webkitUserSelect="",l&&(r.dom.removeClass(r.dom.select("td.mce-item-selected,th.mce-item-selected"),"mce-item-selected"),l=!1)}function s(t){var n,i,s=t.target;if(a&&(u||s!=a)&&("TD"==s.nodeName||"TH"==s.nodeName)){i=o.getParent(s,"table"),i==f&&(u||(u=new e(r,i),u.setStartCell(a),r.getBody().style.webkitUserSelect="none"),u.setEndCell(s),l=!0),n=r.selection.getSel();try{n.removeAllRanges?n.removeAllRanges():n.empty()}catch(c){}t.preventDefault()}}var o=r.dom,u,a,f,l=!0;return r.on("MouseDown",function(e){2!=e.button&&(i(),a=o.getParent(e.target,"td,th"),f=o.getParent(a,"table"))}),o.bind(r.getDoc(),"mouseover",s),r.on("remove",function(){o.unbind(r.getDoc(),"mouseover",s)}),r.on("MouseUp",function(){function e(e,r){var s=new t(e,e);do{if(3==e.nodeType&&0!==n.trim(e.nodeValue).length)return r?i.setStart(e,0):i.setEnd(e,e.nodeValue.length),void 0;if("BR"==e.nodeName)return r?i.setStartBefore(e):i.setEndBefore(e),void 0}while(e=r?s.next():s.prev())}var i,s=r.selection,l,c,h,p,d;if(a){if(u&&(r.getBody().style.webkitUserSelect=""),l=o.select("td.mce-item-selected,th.mce-item-selected"),l.length>0){i=o.createRng(),h=l[0],d=l[l.length-1],i.setStartBefore(h),i.setEndAfter(h),e(h,1),c=new t(h,o.getParent(l[0],"table"));do if("TD"==h.nodeName||"TH"==h.nodeName){if(!o.hasClass(h,"mce-item-selected"))break;p=h}while(h=c.next());e(p),s.setRng(i)}r.nodeChanged(),a=u=f=null}}),r.on("KeyUp",function(){i()}),{clear:i}}}),r(v,[a,c,p,f,d,l,m],function(e,t,n,r,i,s,o){function u(r){function i(e){return e?e.replace(/px$/,""):""}function o(e){return/^[0-9]+$/.test(e)&&(e+="px"),e}function u(e){a("left center right".split(" "),function(t){r.formatter.remove("align"+t,{},e)})}function f(){var e=r.dom,t,n,f;t=r.dom.getParent(r.selection.getStart(),"table"),f=!1,n={width:i(e.getStyle(t,"width")||e.getAttrib(t,"width")),height:i(e.getStyle(t,"height")||e.getAttrib(t,"height")),cellspacing:e.getAttrib(t,"cellspacing"),cellpadding:e.getAttrib(t,"cellpadding"),border:e.getAttrib(t,"border"),caption:!!e.select("caption",t)[0]},a("left center right".split(" "),function(e){r.formatter.matchNode(t,"align"+e)&&(n.align=e)}),r.windowManager.open({title:"Table properties",items:{type:"form",layout:"grid",columns:2,data:n,defaults:{type:"textbox",maxWidth:50},items:[f?{label:"Cols",name:"cols",disabled:!0}:null,f?{label:"Rows",name:"rows",disabled:!0}:null,{label:"Width",name:"width"},{label:"Height",name:"height"},{label:"Cell spacing",name:"cellspacing"},{label:"Cell padding",name:"cellpadding"},{label:"Border",name:"border"},{label:"Caption",name:"caption",type:"checkbox"},{label:"Alignment",minWidth:90,name:"align",type:"listbox",text:"None",maxWidth:null,values:[{text:"None",value:""},{text:"Left",value:"left"},{text:"Center",value:"center"},{text:"Right",value:"right"}]}]},onsubmit:function(){var n=this.toJSON(),i;r.undoManager.transact(function(){r.dom.setAttribs(t,{cellspacing:n.cellspacing,cellpadding:n.cellpadding,border:n.border}),r.dom.setStyles(t,{width:o(n.width),height:o(n.height)}),i=e.select("caption",t)[0],i&&!n.caption&&e.remove(i),!i&&n.caption&&(i=e.create("caption"),i.innerHTML=s.ie?" ":'<br data-mce-bogus="1"/>',t.insertBefore(i,t.firstChild)),u(t),n.align&&r.formatter.apply("align"+n.align,{},t),r.focus(),r.addVisual()})}})}function l(e,t){r.windowManager.open({title:"Merge cells",body:[{label:"Cols",name:"cols",type:"textbox",size:10},{label:"Rows",name:"rows",type:"textbox",size:10}],onsubmit:function(){var n=this.toJSON();r.undoManager.transact(function(){e.merge(t,n.cols,n.rows)})}})}function c(){var e=r.dom,t,n,s=[];s=r.dom.select("td.mce-item-selected,th.mce-item-selected"),t=r.dom.getParent(r.selection.getStart(),"td,th"),!s.length&&t&&s.push(t),t=t||s[0],n={width:i(e.getStyle(t,"width")||e.getAttrib(t,"width")),height:i(e.getStyle(t,"height")||e.getAttrib(t,"height")),scope:e.getAttrib(t,"scope")},n.type=t.nodeName.toLowerCase(),a("left center right".split(" "),function(e){r.formatter.matchNode(t,"align"+e)&&(n.align=e)}),r.windowManager.open({title:"Cell properties",items:{type:"form",data:n,layout:"grid",columns:2,defaults:{type:"textbox",maxWidth:50},items:[{label:"Width",name:"width"},{label:"Height",name:"height"},{label:"Cell type",name:"type",type:"listbox",text:"None",minWidth:90,maxWidth:null,menu:[{text:"Cell",value:"td"},{text:"Header cell",value:"th"}]},{label:"Scope",name:"scope",type:"listbox",text:"None",minWidth:90,maxWidth:null,menu:[{text:"None",value:""},{text:"Row",value:"row"},{text:"Column",value:"col"},{text:"Row group",value:"rowgroup"},{text:"Column group",value:"colgroup"}]},{label:"Alignment",name:"align",type:"listbox",text:"None",minWidth:90,maxWidth:null,values:[{text:"None",value:""},{text:"Left",value:"left"},{text:"Center",value:"center"},{text:"Right",value:"right"}]}]},onsubmit:function(){var t=this.toJSON();r.undoManager.transact(function(){a(s,function(n){r.dom.setAttrib(n,"scope",t.scope),r.dom.setStyles(n,{width:o(t.width),height:o(t.height)}),t.type&&n.nodeName.toLowerCase()!=t.type&&(n=e.rename(n,t.type)),u(n),t.align&&r.formatter.apply("align"+t.align,{},n)}),r.focus()})}})}function h(){var e=r.dom,t,n,s,f,l=[];t=r.dom.getParent(r.selection.getStart(),"table"),n=r.dom.getParent(r.selection.getStart(),"td,th"),a(t.rows,function(t){a(t.cells,function(r){return e.hasClass(r,"mce-item-selected")||r==n?(l.push(t),!1):void 0})}),s=l[0],f={height:i(e.getStyle(s,"height")||e.getAttrib(s,"height")),scope:e.getAttrib(s,"scope")},f.type=s.parentNode.nodeName.toLowerCase(),a("left center right".split(" "),function(e){r.formatter.matchNode(s,"align"+e)&&(f.align=e)}),r.windowManager.open({title:"Row properties",items:{type:"form",data:f,columns:2,defaults:{type:"textbox"},items:[{type:"listbox",name:"type",label:"Row type",text:"None",maxWidth:null,menu:[{text:"Header",value:"thead"},{text:"Body",value:"tbody"},{text:"Footer",value:"tfoot"}]},{type:"listbox",name:"align",label:"Alignment",text:"None",maxWidth:null,menu:[{text:"None",value:""},{text:"Left",value:"left"},{text:"Center",value:"center"},{text:"Right",value:"right"}]},{label:"Height",name:"height"}]},onsubmit:function(){var t=this.toJSON(),n,i,s;r.undoManager.transact(function(){var f=t.type;a(l,function(a){r.dom.setAttrib(a,"scope",t.scope),r.dom.setStyles(a,{height:o(t.height)}),f!=a.parentNode.nodeName.toLowerCase()&&(n=e.getParent(a,"table"),i=a.parentNode,s=e.select(f,n)[0],s||(s=e.create(f),n.firstChild?n.insertBefore(s,n.firstChild):n.appendChild(s)),s.appendChild(a),i.hasChildNodes()||e.remove(i)),u(a),t.align&&r.formatter.apply("align"+t.align,{},a)}),r.focus()})}})}function p(e){return function(){r.execCommand(e)}}function d(e,t){var n,i,o;for(o="<table><tbody>",n=0;t>n;n++){for(o+="<tr>",i=0;e>i;i++)o+="<td>"+(s.ie?" ":"<br>")+"</td>";o+="</tr>"}o+="</tbody></table>",r.insertContent(o)}function v(e,t){function n(){e.disabled(!r.dom.getParent(r.selection.getStart(),t)),r.selection.selectorChanged(t,function(t){e.disabled(!t)})}r.initialized?n():r.on("init",n)}function m(){v(this,"table")}function g(){v(this,"td,th")}function y(){var e="";e='<table role="presentation" class="mce-grid mce-grid-border">';for(var t=0;10>t;t++){e+="<tr>";for(var n=0;10>n;n++)e+='<td><a href="#" data-mce-index="'+n+","+t+'"></a></td>';e+="</tr>"}return e+="</table>",e+='<div class="mce-text-center">0 x 0</div>'}var b,w,E=this;r.addMenuItem("inserttable",{text:"Insert table",icon:"table",context:"table",onhide:function(){r.dom.removeClass(this.menu.items()[0].getEl().getElementsByTagName("a"),"mce-active")},menu:[{type:"container",html:y(),onmousemove:function(e){var t,n,i=e.target;if("A"==i.nodeName){var s=r.dom.getParent(i,"table"),o=i.getAttribute("data-mce-index"),u=e.control.parent().rel;if(o!=this.lastPos){if(o=o.split(","),o[0]=parseInt(o[0],10),o[1]=parseInt(o[1],10),e.control.isRtl()||"tl-tr"==u){for(n=9;n>=0;n--)for(t=0;10>t;t++)r.dom.toggleClass(s.rows[n].childNodes[t].firstChild,"mce-active",t>=o[0]&&n<=o[1]);o[0]=9-o[0],s.nextSibling.innerHTML=o[0]+" x "+(o[1]+1)}else{for(n=0;10>n;n++)for(t=0;10>t;t++)r.dom.toggleClass(s.rows[n].childNodes[t].firstChild,"mce-active",t<=o[0]&&n<=o[1]);s.nextSibling.innerHTML=o[0]+1+" x "+(o[1]+1)}this.lastPos=o}}},onclick:function(e){"A"==e.target.nodeName&&this.lastPos&&(e.preventDefault(),d(this.lastPos[0]+1,this.lastPos[1]+1),this.parent().cancel())}}]}),r.addMenuItem("tableprops",{text:"Table properties",context:"table",onPostRender:m,onclick:f}),r.addMenuItem("deletetable",{text:"Delete table",context:"table",onPostRender:m,cmd:"mceTableDelete"}),r.addMenuItem("cell",{separator:"before",text:"Cell",context:"table",menu:[{text:"Cell properties",onclick:p("mceTableCellProps"),onPostRender:g},{text:"Merge cells",onclick:p("mceTableMergeCells"),onPostRender:g},{text:"Split cell",onclick:p("mceTableSplitCells"),onPostRender:g}]}),r.addMenuItem("row",{text:"Row",context:"table",menu:[{text:"Insert row before",onclick:p("mceTableInsertRowBefore"),onPostRender:g},{text:"Insert row after",onclick:p("mceTableInsertRowAfter"),onPostRender:g},{text:"Delete row",onclick:p("mceTableDeleteRow"),onPostRender:g},{text:"Row properties",onclick:p("mceTableRowProps"),onPostRender:g},{text:"-"},{text:"Cut row",onclick:p("mceTableCutRow"),onPostRender:g},{text:"Copy row",onclick:p("mceTableCopyRow"),onPostRender:g},{text:"Paste row before",onclick:p("mceTablePasteRowBefore"),onPostRender:g},{text:"Paste row after",onclick:p("mceTablePasteRowAfter"),onPostRender:g}]}),r.addMenuItem("column",{text:"Column",context:"table",menu:[{text:"Insert column before",onclick:p("mceTableInsertColBefore"),onPostRender:g},{text:"Insert column after",onclick:p("mceTableInsertColAfter"),onPostRender:g},{text:"Delete column",onclick:p("mceTableDeleteCol"),onPostRender:g}]});var S=[];a("inserttable tableprops deletetable | cell row column".split(" "),function(e){"|"==e?S.push({text:"-"}):S.push(r.menuItems[e])}),r.addButton("table",{type:"menubutton",title:"Table",menu:S}),s.isIE||r.on("click",function(e){e=e.target,"TABLE"===e.nodeName&&(r.selection.select(e),r.nodeChanged())}),E.quirks=new t(r),r.on("Init",function(){b=r.windowManager,E.cellSelection=new n(r)}),a({mceTableSplitCells:function(e){e.split()},mceTableMergeCells:function(e){var t,n,i;i=r.dom.getParent(r.selection.getStart(),"th,td"),i&&(t=i.rowSpan,n=i.colSpan),r.dom.select("td.mce-item-selected,th.mce-item-selected").length?e.merge():l(e,i)},mceTableInsertRowBefore:function(e){e.insertRow(!0)},mceTableInsertRowAfter:function(e){e.insertRow()},mceTableInsertColBefore:function(e){e.insertCol(!0)},mceTableInsertColAfter:function(e){e.insertCol()},mceTableDeleteCol:function(e){e.deleteCols()},mceTableDeleteRow:function(e){e.deleteRows()},mceTableCutRow:function(e){w=e.cutRows()},mceTableCopyRow:function(e){w=e.copyRows()},mceTablePasteRowBefore:function(e){e.pasteRows(w,!0)},mceTablePasteRowAfter:function(e){e.pasteRows(w)},mceTableDelete:function(e){e.deleteTable()}},function(t,n){r.addCommand(n,function(){var n=new e(r);n&&(t(n),r.execCommand("mceRepaint"),E.cellSelection.clear())})}),a({mceInsertTable:function(){f()},mceTableRowProps:h,mceTableCellProps:c},function(e,t){r.addCommand(t,function(t,n){e(n)})})}var a=r.each;o.add("table",u)}),o([a,c,p,v])}(this);