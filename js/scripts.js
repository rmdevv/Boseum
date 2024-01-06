var artworkSection = document.querySelector('#artwork_section')
var artworkImage = document.querySelector('#artwork_image')
var additionalImagesCarousel = document.querySelector(
    '#additional_images_carousel'
)

var imageSlideElements
if (additionalImagesCarousel) {
    imageSlideElements =
        additionalImagesCarousel.querySelectorAll('.thumbnail_slide')
}

var handleImageClick = function (element) {
    Array.from(document.querySelectorAll('.thumbnail_slide')).forEach(function (
        e
    ) {
        e.classList.remove('is_active')
    })
    element.classList.add('is_active')

    artworkImage.src = element.querySelector('img').src
    var download_link = document.querySelector('#download_image')
    download_link &&
        download_link.setAttribute('href', element.querySelector('img').src),
        download_link.setAttribute('download', element.querySelector('img').src)

    var enlarge_link = document.querySelector('#enlarge_link')
    enlarge_link &&
        enlarge_link.setAttribute('href', element.querySelector('img').src)
}

if (artworkImage && additionalImagesCarousel && imageSlideElements) {
    imageSlideElements.forEach(function (element) {
        element.addEventListener('click', function (event) {
            handleImageClick(element)
        })

        element.addEventListener('keypress', function (event) {
            handleImageClick(element)
        })
    })
}
