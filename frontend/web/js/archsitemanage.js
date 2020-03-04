
function onViewOriginal(event){

    changePictures("image-origin");//, ".image-dstretch, .image-drawing, .image-reconstraction, .image-overlay");
}

function onViewDstratch(event){

    changePictures("image-dstretch");//, ".image-origin, .image-drawing, .image-reconstraction, .image-overlay");
}

function onViewDrawing(event){

    changePictures("image-drawing"); //, ".image-dstretch, .image-origin, .image-reconstraction, .image-overlay");
}

function onViewReconstraction(event){

    changePictures("image-reconstraction"); //, ".image-dstretch, .image-drawing, .image-origin, .image-overlay");
}
function onViewSuperimposition(event){

    changePictures("image-overlay"); //,  ".image-dstretch, .image-drawing, .image-reconstraction, .image-origin");
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
/*     newImages = document.getElementsByClassName(newImages);
    while(newImages.item(i) != null){
        newImages.item(i).style.display = 'block';
        i++;
    }
    i = 0;
    otherImages = document.querySelectorAll(otherImages);
    while(otherImages.item(i) != null){
        if(otherImages.item(i).style.display != 'none')
            otherImages.item(i).style.display = 'none';
        i++;
    }; */
}

vieworigin.addEventListener("click", onViewOriginal);
viewdstratch.addEventListener("click", onViewDstratch);
viewdrawing.addEventListener("click", onViewDrawing);
viewreconstraction.addEventListener("click", onViewReconstraction);
viewsuperimposition.addEventListener("click", onViewSuperimposition);