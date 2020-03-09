
function onViewOriginal(event){

    changePictures("image-origin");
}

function onViewDStretch(event){

    changePictures("image-dstretch");
}

function onViewDrawing(event){

    changePictures("image-drawing"); 
}

function onViewReconstraction(event){

    changePictures("image-reconstraction"); 
}
function onViewOverlay(event){

    changePictures("image-overlay"); 
}

function changePictures(newImages){
    i = 0;
    petroglyphs = document.getElementsByClassName("row");
    while(petroglyphs.item(i) !=null){
        if(petroglyphs.item(i).className != "row"){
            i++;
            continue;
        }
        item = petroglyphs.item(i);
        image = item.querySelector("div." + newImages) == null ? 
                image = item.querySelector("div.image-origin"): item.querySelector("div." + newImages); 

        if(image != null)
            image.style.display = 'block';
        
        j = 0;
        while(item.children.item(j) != null){
            if(item.children.item(j).style.display != 'none' 
                && item.children.item(j) != image)
                item.children.item(j).style.display = 'none';
            j++;
        }
        i++;
    }

}

vieworigin.addEventListener("click", onViewOriginal);
viewdstretch.addEventListener("click", onViewDStretch);
viewdrawing.addEventListener("click", onViewDrawing);
viewreconstruction.addEventListener("click", onViewReconstraction);
viewoverlay.addEventListener("click", onViewOverlay);