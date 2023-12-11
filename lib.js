async function loadSection(target, source) {
    const response = await fetch(source);
    const text = await response.text();
    document.getElementById(target).innerHTML = text;
}


function loadJS(src) {
    var js = document.createElement("script");
    js.src = src + "&t=" + new Date().getTime();
    js.setAttribute("type", "module");
    document.head.appendChild(js);
}