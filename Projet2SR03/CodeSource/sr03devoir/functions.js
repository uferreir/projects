function showDetails (subid) {
    let infos = document.getElementById(subid);
    if (infos.style.display != "block" ) {
        infos.style.display = "block";
    } else {
        infos.style.display = "none";
    }
}

function redirection(link) {
  location.replace(link);
}
