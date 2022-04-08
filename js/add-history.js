if(viewHistory) {
    var ptitle= document.getElementsByTagName('title')[0].innerHTML;
    var noname = ptitle.split(" | ");
    var page = {
        "title": noname[0],
        "url": location.href
        // "time": new Date()
        // "author": ...
    };
    viewHistory.addHistory(page);
}