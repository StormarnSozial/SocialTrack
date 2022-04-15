let links;

function insert(id, content) {
    const insertobj = document.getElementById(id);
    insertobj.innerHTML = content;
    const scripts = insertobj.getElementsByTagName("script");
    for(let i=0; i<scripts.length; i++){
        eval(scripts[i].innerHTML)
    }
}

function prepare() {
    console.log('preparing ajax ...');
    links = document.getElementsByTagName('a');
    for(let i = 0; i < links.length; i++) {
        if(links[i].dataset.enableajax !== "off") {
            (function(){
                console.log(links[i].href);
                const buf = links[i].href;
                links[i].addEventListener('click', function(){ajax(buf)}, false);
                links[i].href = 'javascript: void(0)';
            }())
        }
    }
}
function ajax(curl, push_state = true) {
    let url;
    if(curl.indexOf('?') > -1){
        url = curl + "&ajax";
    }
    else{
        url = curl + "?ajax";
    }
    document.getElementById("loadable_content").style.animation = "fadeout 0.5s";
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        insert("loadable_content", this.responseText);
        document.getElementById("loadable_content").style.animation = "fadein 0.5s";
        prepare();
        }
    xhttp.open("GET", url, true);
    xhttp.send();
    if(push_state){
        window.history.pushState(curl, curl, curl);
    }
}

window.onpopstate = function() {
    ajax(document.location.toString(), false);
};

window.onload = prepare;
