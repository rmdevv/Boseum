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

var handleImageClick = (element) => {
    Array.from(document.querySelectorAll('.thumbnail_slide')).forEach((e) => {
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
    imageSlideElements.forEach((element) => {
        element.addEventListener('click', (event) => {
            handleImageClick(element)
        })

        element.addEventListener('keypress', (event) => {
            handleImageClick(element)
        })
    })
}

const paginationNumbers = document.getElementById('pagination_numbers')
const paginatedList = document.getElementById('paginated_section')
if (paginatedList) {
    const items = paginatedList.querySelectorAll('.gallery_item')
    const prevButton = document.getElementById('prev_pag_button')
    const nextButton = document.getElementById('next_pag_button')

    const paginationLimit = 10
    let currentPage = 1
    const pageCount = Math.ceil(items.length / paginationLimit)

    const disableButton = (button) => {
        button.setAttribute('disabled', true)
    }

    const enableButton = (button) => {
        button.removeAttribute('disabled')
    }

    const backToTop = () => {
        document.getElementById('top_gallery').scrollIntoView()
    }

    const handlePageButtonsStatus = () => {
        if (currentPage === 1) {
            disableButton(prevButton)
        } else {
            enableButton(prevButton)
        }

        if (pageCount === currentPage) {
            disableButton(nextButton)
        } else {
            enableButton(nextButton)
        }
    }

    const handleActivePageNumber = () => {
        document.querySelectorAll('.pagination_number').forEach((button) => {
            button.classList.remove('active')
            const pageIndex = Number(button.getAttribute('page-index'))
            if (pageIndex == currentPage) {
                button.classList.add('active')
            }
        })
    }

    const appendPageNumber = (index) => {
        const pageNumber = document.createElement('button')
        pageNumber.className =
            'button_reverse pagination_controls pagination_number'
        pageNumber.innerHTML = index
        pageNumber.setAttribute('page-index', index)
        pageNumber.setAttribute('aria-label', 'Pagina ' + index)

        paginationNumbers.appendChild(pageNumber)
    }

    const getPaginationNumbers = () => {
        for (let i = 1; i <= pageCount; i++) {
            appendPageNumber(i)
        }
    }

    const setCurrentPage = (pageNum, scroll = true) => {
        currentPage = pageNum

        handleActivePageNumber()
        handlePageButtonsStatus()

        const prevRange = (pageNum - 1) * paginationLimit
        const currRange = pageNum * paginationLimit

        items.forEach((item, index) => {
            item.classList.add('hidden')
            if (index >= prevRange && index < currRange) {
                item.classList.remove('hidden')
            }
        })

        scroll && backToTop()
    }

    getPaginationNumbers()
    setCurrentPage(1, false)

    prevButton.addEventListener('click', () => {
        setCurrentPage(currentPage - 1)
    })

    nextButton.addEventListener('click', () => {
        setCurrentPage(currentPage + 1)
    })

    document.querySelectorAll('.pagination_number').forEach((button) => {
        const pageIndex = Number(button.getAttribute('page-index'))

        if (pageIndex) {
            button.addEventListener('click', () => {
                setCurrentPage(pageIndex)
            })
        }
    })

    const backToTopButton = document.getElementById('back_to_top')

    backToTopButton.addEventListener('click', () => {
        backToTop()
    })
}

const startDate = document.getElementById('start_date')
const startDateFuture = document.getElementById('start_date_future')
const endDate = document.getElementById('end_date')

if ((startDate || startDateFuture) && endDate) {
    today = new Date()

    if (startDate) {
        startDate.value = today.toLocaleDateString('fr-ca')
        endDate.value = today.toLocaleDateString('fr-ca')
        endDate.min = startDate.value
        startDate.max = endDate.value

        startDate.addEventListener('input', () => {
            var min_limit = startDate.value
            endDate.min = min_limit
        })
    }

    if (startDateFuture) {
        startDateFuture.value = today.toLocaleDateString('fr-ca')
        endDate.value = today.toLocaleDateString('fr-ca')
        endDate.min = startDateFuture.value
        startDateFuture.min = today.toLocaleDateString('fr-ca')
        startDateFuture.max = endDate.value

        startDateFuture.addEventListener('input', () => {
            var min_limit = startDateFuture.value
            endDate.min = min_limit
        })
    }
    endDate.addEventListener('input', () => {
        var max_limit = endDate.value
        if (startDate) {
            startDate.max = max_limit
        }
        if (startDateFuture) {
            startDateFuture.max = max_limit
        }
    })
}

const pastDate = document.getElementById('birthdate')
pastDate && (pastDate.max = new Date().toLocaleDateString('fr-ca'))

const additionalImagesInput = document.getElementById('additional-images-input')
if (additionalImagesInput) {
    additionalImagesInput.addEventListener('change', (event) => {
        const uploadImgContainer = document.querySelector(
            '#additional-images-viewer'
        )
        uploadImgContainer.innerHTML = ''

        if (event.target.files) {
            files = event.target.files
            const fragment = document.createDocumentFragment()

            for (let i = 0; i < files.length; i++) {
                const imgContainer = document.createElement('div')
                imgContainer.className = 'uploaded_image'

                const img = document.createElement('img')
                img.src = URL.createObjectURL(files[i])
                img.setAttribute('add-img-id', i)
                img.alt = files[i].name
                files[i].imgId = i

                const removeBtn = document.createElement('button')
                removeBtn.type = 'button'
                removeBtn.className = 'remove_btn'
                removeBtn.ariaLabel = "Elimina l'immagine"
                removeBtn.addEventListener('click', (e) => {
                    const removedImgContainer = e.target.parentNode
                    removedImgContainer.remove()

                    updateFileList(i)
                })

                imgContainer.appendChild(img)
                imgContainer.appendChild(removeBtn)
                fragment.appendChild(imgContainer)
            }

            uploadImgContainer.appendChild(fragment)
        }
    })

    const updateFileList = (idRemoved) => {
        const dt = new DataTransfer()
        const uploadInput = document.getElementById('additional-images-input')
        const updatedFiles = uploadInput.files

        for (let i = 0; i < updatedFiles.length; i++) {
            if (idRemoved != updatedFiles[i].imgId)
                dt.items.add(updatedFiles[i])
        }

        uploadInput.files = dt.files
    }
}

const mainImageInput = document.getElementById('main-image-input')
if (mainImageInput) {
    mainImageInput.addEventListener('change', (event) => {
        const mainImageContainer = document.querySelector('#main-image-viewer')
        mainImageContainer.innerHTML = ''

        if (event.target.files) {
            file = event.target.files[0]

            const imgContainer = document.createElement('div')
            imgContainer.className = 'uploaded_image'

            const img = document.createElement('img')
            img.src = URL.createObjectURL(file)
            img.alt = file.name

            imgContainer.appendChild(img)
            mainImageContainer.appendChild(imgContainer)
        }
    })
}

const profileImageInput = document.getElementById('profile-image-input')
if (profileImageInput) {
    profileImageInput.addEventListener('change', (event) => {
        const profileImage = document.querySelector('#profile-image')

        if (event.target.files) {
            file = event.target.files[0]

            profileImage.src = URL.createObjectURL(file)
        }
    })
}

const logoutButton = document.getElementById('logout_button')
if (logoutButton) {
    logoutButton.addEventListener('click', () => {
        if (window.confirm('Vuoi veramente uscire?')) {
            window.open('../php/logout.php')
        }
    })
}
