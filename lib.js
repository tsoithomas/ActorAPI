async function loadSection(target, source) {
    const response = await fetch(source);
    const text = await response.text();
    document.getElementById(target).innerHTML = text;
}