console.log("Script Page Loaded");
const sidebar =
document.querySelector(".sidebar");

const toggleSidebar =
document.getElementById("toggleSidebar");

toggleSidebar.addEventListener(
    "click",
    () => {

        sidebar.classList.toggle("hide");

    }
);

const editor =
document.querySelector(".editor");

const mode =
document.getElementById("modeSelect");

mode.addEventListener(
    "change",
    () => {

        if(mode.value==="edit")
        {
            editor.contentEditable=true;
        }
        else
        {
            editor.contentEditable=false;
        }

    }
);

const zoom =
document.getElementById("zoomSelect");

zoom.addEventListener(
    "change",
    () => {

        document.querySelector(".editor")
        .style.transform =
        `scale(${zoom.value/100})`;

    }
);