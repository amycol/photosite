function openTab(tabName, click) {
    var i, content, tab;
  content = document.getElementsByClassName("content");
  for (i = 0; i < content.length; i++) {
    content[i].style.display = "none";
  }
  tab = document.getElementsByClassName("tab");
  for (i = 0; i < tab.length; i++) {
    tab[i].className = tab[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

function openTab(tabName) {
    var tabs, content;
    //Make all tabs inactive
    tabs = document.getElementsByClassName("tab");
    for (var i = 0; i < tabs.length; i++) {
        var el = tabs[i]
        el.className = el.className.replace(" active", "")
    }
    click.currentTarget.className += " active"; //Make clicked tab active
    //Hide all content pages
    content = document.getElementsByClassName("content")
}