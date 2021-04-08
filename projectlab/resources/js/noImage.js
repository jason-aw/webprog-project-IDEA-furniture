function noImage(img){
    console.log('hey');
    img.onerror = "";
    img.src = asset('storage/images/thumbnail/noideayet.jpg');
    return true;
}