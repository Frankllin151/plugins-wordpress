document.addEventListener('DOMContentLoaded', function() {
  const slider = new Slide('.slide', '.slide-wrapper');
  slider.init();
});


class Gallery {
  constructor(){
    this.gallery = document.querySelector('[data-gallery="gallery"]');
    this.galleryList = document.querySelectorAll('[data-gallery="list"]');
    this.galleryMain = document.querySelector('[data-gallery="main"]');

    this.ChangeImg = this.ChangeImg.bind(this);
  }

  ChangeImg({currentTarget}) {
    this.galleryMain.src = currentTarget.src
  }
 addChangeEvent()
 {
  this.galleryList.forEach(img => {
    img.addEventListener("click" , this.ChangeImg)
    img.addEventListener("mouseover" , this.ChangeImg)
  })
 }

 init(){
  if(this.gallery){
 this.addChangeEvent()
  }
 }
}
 const gallery = new Gallery();
 gallery.init();
 console.log(gallery);
