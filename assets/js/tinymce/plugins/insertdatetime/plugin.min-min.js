tinymce.PluginManager.add("insertdatetime",function(e){function t(t,n){function r(e,t){if(e=""+e,e.length<t)for(var n=0;n<t-e.length;n++)e="0"+e;return e}return n=n||new Date,t=t.replace("%D","%m/%d/%Y"),t=t.replace("%r","%I:%M:%S %p"),t=t.replace("%Y",""+n.getFullYear()),t=t.replace("%y",""+n.getYear()),t=t.replace("%m",r(n.getMonth()+1,2)),t=t.replace("%d",r(n.getDate(),2)),t=t.replace("%H",""+r(n.getHours(),2)),t=t.replace("%M",""+r(n.getMinutes(),2)),t=t.replace("%S",""+r(n.getSeconds(),2)),t=t.replace("%I",""+((n.getHours()+11)%12+1)),t=t.replace("%p",""+(n.getHours()<12?"AM":"PM")),t=t.replace("%B",""+e.translate(a[n.getMonth()])),t=t.replace("%b",""+e.translate(u[n.getMonth()])),t=t.replace("%A",""+e.translate(o[n.getDay()])),t=t.replace("%a",""+e.translate(s[n.getDay()])),t=t.replace("%%","%")}function n(n){var r=t(n);if(e.settings.insertdatetime_element){var i;i=/%[HMSIp]/.test(n)?t("%Y-%m-%dT%H:%M"):t("%Y-%m-%d"),r='<time datetime="'+i+'">'+r+"</time>";var s=e.dom.getParent(e.selection.getStart(),"time");if(s)return e.dom.setOuterHTML(s,r),void 0}e.insertContent(r)}var r,i,s="Sun Mon Tue Wed Thu Fri Sat Sun".split(" "),o="Sunday Monday Tuesday Wednesday Thursday Friday Saturday Sunday".split(" "),u="Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),a="January February March April May June July August September October November December".split(" "),f=[];e.addCommand("mceInsertDate",function(){n(e.getParam("insertdatetime_dateformat",e.translate("%Y-%m-%d")))}),e.addCommand("mceInsertTime",function(){n(e.getParam("insertdatetime_timeformat",e.translate("%H:%M:%S")))}),e.addButton("inserttime",{type:"splitbutton",title:"Insert time",onclick:function(){n(r||i)},menu:f}),tinymce.each(e.settings.insertdatetime_formats||["%H:%M:%S","%Y-%m-%d","%I:%M:%S %p","%D"],function(e){i||(i=e),f.push({text:t(e),onclick:function(){r=e,n(e)}})}),e.addMenuItem("insertdatetime",{icon:"date",text:"Insert date/time",menu:f,context:"insert"})});